<?php

namespace App\Services\Notifications\Handlers;

use App\Enums\GameSessionStatus;
use App\Enums\NotificationStatus;
use App\Enums\RegistrationStatus;
use App\Mail\GameSessionCanceledMail;
use App\Models\GameSession;
use App\Models\Notification;
use App\Models\Registration;
use App\Services\Notifications\{
    NotificationContext,
    InAppTemplateFactory
};
use Illuminate\Support\Facades\Log;

class SessionCanceledHandler extends NotificationHandlerBase
{
    protected ?GameSession $session = null;

    /**
     * Check if the notification is still relevant.
     * (Session still exists and is canceled)
     */
    protected function isStillRelevant(Notification $n): bool
    {
        $this->session = GameSession::find($n->data['session_id'] ?? null);

        if (! $this->session) {
            return false;
        }

        // Only relevant if session is actually canceled
        if ($this->session->status !== GameSessionStatus::CANCELLED) {
            return false;
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
            return false;
        }

        return true;
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
            'link'    => $ctx->session ? route('game-session.interaction.show', $ctx->session->uuid) : null,
        ]);

        Log::info("SessionCanceledHandler sent notification #{$n->id} via: " . implode(', ', $channels));
    }
}
