<?php

namespace App\Services\Notifications\Handlers;

use App\Enums\{GameSessionStatus, RegistrationStatus, NotificationStatus};
use App\Mail\GameSessionReminderMail;
use App\Models\{GameSession, Notification, Registration};
use App\Services\Notifications\{NotificationContext, InAppTemplateFactory, Support\RelevanceResult};
use Illuminate\Support\Facades\Log;

class SessionReminderHandler extends NotificationHandlerBase
{
    protected ?GameSession $session = null;

    /**
     * Determine if the reminder is still relevant.
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
            return RelevanceResult::fail('Session is not open to people to join.');
        }

        // The session must still have available spots
        if (! $this->session->hasOpenPositions()) {
            return RelevanceResult::fail('Session dose not have any open positions.');
        }

        // User must still be in RemindMe2Days status for this session
        $isRemindUser = Registration::query()
            ->where('user_id', $n->user->id)
            ->where('game_session_id', $this->session->id)
            ->where('status', RegistrationStatus::RemindMe2Days)
            ->exists();

        if (! $isRemindUser) {
            return RelevanceResult::fail('User must still be in RemindMe2Days status for this session.');
        }

        return RelevanceResult::ok();
    }

    /**
     * Build the context for the notification.
     */
    protected function buildContext(Notification $n): NotificationContext
    {
        return new NotificationContext(
            user: $n->user,
            session: $this->session
        );
    }

    /**
     * Send the notification via allowed channels.
     */
    protected function send(Notification $n, NotificationContext $ctx, array $channels): void
    {
        $template = (new InAppTemplateFactory())->make($n->type, [
            'session' => $ctx->session,
        ]);

        $this->channels->via($channels)->send($n, [
            'email'   => new GameSessionReminderMail($ctx->user, $ctx->session),
            'title'   => $template['title'],
            'message' => $template['message'],
            'link'    => $template['link'],
        ]);

        Log::info("SessionReminderHandler sent notification #{$n->id} via: " . implode(', ', $channels));
    }
}
