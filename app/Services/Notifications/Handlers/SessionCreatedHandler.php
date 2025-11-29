<?php

namespace App\Services\Notifications\Handlers;

use App\Enums\GameSessionStatus;
use App\Enums\NotificationStatus;
use App\Enums\NotificationSubscriptionType;
use Illuminate\Support\Facades\Log;
use App\Models\{Notification, GameSession, NotificationSubscription};
use App\Mail\GameSessionCreatedMail;
use App\Services\Notifications\{NotificationContext,
    InAppTemplateFactory,
    Policies\SessionCreatedPolicy,
    Support\RelevanceResult};

class SessionCreatedHandler extends NotificationHandlerBase
{
    protected ?GameSession $session = null;

    protected function isStillRelevant(Notification $n): RelevanceResult
    {
        $this->session = GameSession::find($n->data['session_id'] ?? null);

        // Session must still exist
        if (! $this->session) {
            return RelevanceResult::fail('Session not found');
        }

        // The session must start in the future
        if ($this->session->start_at->isPast()) {
            return RelevanceResult::fail('Session already started.');
        }

        // Must be open to participants
        if (! in_array($this->session->status, [
            GameSessionStatus::RECRUITING_PARTICIPANTS,
            GameSessionStatus::CONFIRMED_BY_ORGANIZER,
        ], true)) {
            return RelevanceResult::fail('Session is not in a status where the users can join.');
        }

        // Must still have open positions
        if (! $this->session->hasOpenPositions()) {
            return RelevanceResult::fail('Session has no open spots at the moment');
        }

        return RelevanceResult::ok();
    }


    protected function buildContext(Notification $n): NotificationContext
    {
        return new NotificationContext(
            user: $n->user,
            session: $this->session
        );
    }

    protected function send(Notification $n, NotificationContext $ctx, array $channels): void
    {
        $template = (new InAppTemplateFactory())->make($n->type, ['session' => $ctx->session]);

        $this->channels->via($channels)->send($n, [
            'email'   => new GameSessionCreatedMail($ctx->user, $ctx->session),
            'title'   => $template['title'],
            'message' => $template['message'],
            'link'    => $template['link'],
        ]);
    }
}
