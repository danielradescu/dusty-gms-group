<?php

namespace App\Services\Notifications\Handlers;

use App\Enums\GameSessionStatus;
use App\Enums\NotificationStatus;
use App\Enums\RegistrationStatus;
use App\Mail\GameSessionCanceledMail;
use App\Models\GameSession;
use App\Models\Notification;
use App\Models\Registration;
use App\Services\Notifications\{NotificationContext, InAppTemplateFactory, Support\RelevanceResult};
use Illuminate\Support\Facades\Log;

class SessionCanceledHandler extends NotificationHandlerBase
{
    protected ?GameSession $session = null;

    /**
     * Check if the notification is still relevant.
     * (Session still exists and is canceled)
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

        // Only relevant if session is actually canceled
        if ($this->session->status !== GameSessionStatus::CANCELLED) {
            return RelevanceResult::fail('Session is not in status cancelled');
        }

        // Must still be a registered participant (Confirmed/Open/RemindMe)
        $userIsParticipant = Registration::query()
            ->where('game_session_id', $this->session->id)
            ->where('user_id', $n->user->id)
            ->whereIn('status', [
                RegistrationStatus::Confirmed,
                RegistrationStatus::OpenPosition,
                RegistrationStatus::RemindMe2Days,
            ])
            ->exists();

        if (! $userIsParticipant) {
            return RelevanceResult::fail('User is not a participant to this session (Confirmed/Open/RemindMe)');
        }

        return RelevanceResult::ok();
    }

    /**
     * Build context.
     */
    protected function buildContext(Notification $n): NotificationContext
    {
        return new NotificationContext(
            user: $n->user,
            session: $this->session ?? GameSession::find($n->data['session_id'])
        );
    }

    /**
     * Send via resolved CHANNELS.
     */
    protected function send(Notification $n, NotificationContext $ctx, array $channels): void
    {
        $template = (new InAppTemplateFactory())->make($n->type, [
            'session' => $ctx->session,
        ]);

        $this->channels->via($channels)->send($n, [
            'email'   => new GameSessionCanceledMail($ctx->user, $ctx->session),
            'title'   => $template['title'],
            'message' => $template['message'],
            'link'    => $template['link'],
        ]);

        Log::info("SessionCanceledHandler sent notification #{$n->id} via: " . implode(', ', $channels));
    }
}
