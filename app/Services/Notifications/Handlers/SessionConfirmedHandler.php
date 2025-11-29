<?php

namespace App\Services\Notifications\Handlers;

use App\Enums\GameSessionStatus;
use App\Enums\NotificationStatus;
use App\Enums\RegistrationStatus;
use App\Mail\GameSessionConfirmedMail;
use App\Models\GameSession;
use App\Models\Notification;
use App\Models\Registration;
use App\Services\Notifications\{
    NotificationContext,
    InAppTemplateFactory
};
use Illuminate\Support\Facades\Log;

class SessionConfirmedHandler extends NotificationHandlerBase
{
    protected ?GameSession $session = null;

    /**
     * Check if the notification is still relevant.
     * (Session still exists and is confirmed)
     */
    protected function isStillRelevant(Notification $n): bool
    {
        $this->session = GameSession::find($n->data['session_id'] ?? null);

        if (! $this->session) {
            return false;
        }

        // Only relevant if the session is confirmed
        if ($this->session->status !== GameSessionStatus::CONFIRMED_BY_ORGANIZER) {
            return false;
        }

        //relevant if this is a confirmed user to the session
        if (! Registration::where('user_id', $n->user->id)
            ->where('game_session_id', $this->session->id)
            ->where('status', RegistrationStatus::Confirmed)
            ->exists()
        ) {
            return false;
        }

        return true;
    }

    /**
     * Build the context object for templating and email.
     */
    protected function buildContext(Notification $n): NotificationContext
    {
        return new NotificationContext(
            user: $n->user,
            session: $this->session ?? GameSession::find($n->data['session_id'])
        );
    }

    /**
     * Send the notification via resolved channels.
     */
    protected function send(Notification $n, NotificationContext $ctx, array $channels): void
    {
        $template = (new InAppTemplateFactory())->make($n->type, [
            'session' => $ctx->session,
        ]);

        $this->channels->via($channels)->send($n, [
            'email'   => new GameSessionConfirmedMail($ctx->user, $ctx->session),
            'title'   => $template['title'],
            'message' => $template['message'],
            'link'    => $ctx->session ? route('game-session.interaction.show', $ctx->session->uuid) : null,
        ]);

        Log::info("SessionConfirmedHandler sent notification #{$n->id} via: " . implode(', ', $channels));
    }
}
