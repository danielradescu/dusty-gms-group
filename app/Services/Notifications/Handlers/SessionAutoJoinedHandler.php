<?php

namespace App\Services\Notifications\Handlers;

use Carbon\Carbon;
use App\Enums\{GameSessionStatus, RegistrationStatus};
use App\Mail\GameSessionAutoJoinedMail;
use App\Models\{GameSession, Notification, Registration};
use App\Services\Notifications\{NotificationContext, InAppTemplateFactory, Support\RelevanceResult};
use Illuminate\Support\Facades\Log;

class SessionAutoJoinedHandler extends NotificationHandlerBase
{
    protected ?GameSession $session = null;

    /**
     * Determine if this notification is still relevant.
     */
    protected function isStillRelevant(Notification $n): RelevanceResult
    {
        $this->session = GameSession::find($n->data['session_id'] ?? null);

        if (! $this->session) {
            return RelevanceResult::fail('Session not found');
        }

        // The session must start in the future
        if ($this->session->start_at->isPast()) {
            return RelevanceResult::fail('Session already started.');
        }

        // Session must be open for joins status
        if (! in_array($this->session->status, [
            GameSessionStatus::RECRUITING_PARTICIPANTS,
            GameSessionStatus::CONFIRMED_BY_ORGANIZER,
        ], true)) {
            return RelevanceResult::fail('Session is not open to join.');
        }

        // Check that user is still confirmed in this session
        $isConfirmed = Registration::query()
            ->where('user_id', $n->user->id)
            ->where('game_session_id', $this->session->id)
            ->where('status', RegistrationStatus::Confirmed)
            ->exists();

        if (! $isConfirmed) {
            return RelevanceResult::fail("User doesn't have a confirmed registration in the session anymore.");
        }

        return RelevanceResult::ok();
    }

    /**
     * Build notification context.
     */
    protected function buildContext(Notification $n): NotificationContext
    {
        return new NotificationContext(
            user: $n->user,
            session: $this->session,
            targetDate: isset($n->data['target_date'])
                ? Carbon::parse($n->data['target_date'])
                : null,

        );
    }

    /**
     * Send via appropriate channels.
     */
    protected function send(Notification $n, NotificationContext $ctx, array $channels): void
    {
        $template = (new InAppTemplateFactory())->make($n->type, [
            'session' => $ctx->session,
            'target_date' => $ctx->target_date ?? null,
        ]);

        $this->channels->via($channels)->send($n, [
            'email'   => new GameSessionAutoJoinedMail(
                $ctx->user,
                $ctx->session,
                $ctx->targetDate ?? null
            ),
            'title'   => $template['title'],
            'message' => $template['message'],
            'link'    => $template['link'],
        ]);

        Log::info("SessionAutoJoinedHandler sent notification #{$n->id} via: " . implode(', ', $channels));
    }
}
