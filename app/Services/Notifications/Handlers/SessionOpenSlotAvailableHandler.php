<?php

namespace App\Services\Notifications\Handlers;

use App\Enums\{
    GameSessionStatus,
    RegistrationStatus,
};
use App\Mail\GameSessionOpenSlotAvailableMail;
use App\Models\{
    GameSession,
    Notification,
    Registration,
};
use App\Services\Notifications\{
    NotificationContext,
    InAppTemplateFactory,
};
use App\Services\Notifications\Support\RelevanceResult;
use Illuminate\Support\Facades\Log;

class SessionOpenSlotAvailableHandler extends NotificationHandlerBase
{
    protected ?GameSession $session = null;

    /**
     * Check if the notification is still relevant.
     */
    protected function isStillRelevant(Notification $n): RelevanceResult
    {
        $this->session = GameSession::find($n->data['session_id'] ?? null);

        if (! $this->session) {
            return RelevanceResult::fail('Session not found');
        }

        // The session must not have started
        if ($this->session->start_at->isPast()) {
            return RelevanceResult::fail('Session already started');
        }

        // The session must be in a joinable state
        if (! in_array($this->session->status, [
            GameSessionStatus::RECRUITING_PARTICIPANTS,
            GameSessionStatus::CONFIRMED_BY_ORGANIZER,
        ], true)) {
            return RelevanceResult::fail('Session not open for new participants');
        }

        // The session must have open positions
        if (! $this->session->hasOpenPositions()) {
            return RelevanceResult::fail('No open positions available');
        }

        // Check the user's registration
        $registration = Registration::query()
            ->where('user_id', $n->user->id)
            ->where('game_session_id', $this->session->id)
            ->first();

        if (! $registration) {
            return RelevanceResult::fail('User registration not found');
        }

        // The user must still be waiting, not confirmed or declined
        if (! in_array($registration->status, [
            RegistrationStatus::RemindMe2Days,
            RegistrationStatus::OpenPosition,
        ], true)) {
            return RelevanceResult::fail('User no longer waiting for a slot');
        }

        return RelevanceResult::ok();
    }

    /**
     * Build context for email and in-app message.
     */
    protected function buildContext(Notification $n): NotificationContext
    {
        return new NotificationContext(
            user: $n->user,
            session: $this->session
        );
    }

    /**
     * Send notification via allowed channels.
     */
    protected function send(Notification $n, NotificationContext $ctx, array $channels): void
    {
        $template = (new InAppTemplateFactory())->make($n->type, [
            'session' => $ctx->session,
        ]);

        $this->channels->via($channels)->send($n, [
            'email'   => new GameSessionOpenSlotAvailableMail($ctx->user, $ctx->session),
            'title'   => $template['title'],
            'message' => $template['message'],
            'link'    => $template['link'],
        ]);

        Log::info("SessionOpenSlotAvailableHandler sent notification #{$n->id} via: " . implode(', ', $channels));
    }
}
