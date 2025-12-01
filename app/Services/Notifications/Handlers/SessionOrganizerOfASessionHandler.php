<?php

namespace App\Services\Notifications\Handlers;

use App\Enums\GameSessionStatus;
use App\Mail\OrganizerOfASessionMail;
use App\Models\{
    GameSession,
    Notification
};
use App\Services\Notifications\{
    NotificationContext,
    InAppTemplateFactory
};
use App\Services\Notifications\Support\RelevanceResult;
use Illuminate\Support\Facades\Log;

class SessionOrganizerOfASessionHandler extends NotificationHandlerBase
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

        // Session must be in valid state
        if (! $this->session->canChangeStatus()) {
            return RelevanceResult::fail('Session is not open for organization');
        }

        // Session must be in the future
        if ($this->session->start_at->isPast()) {
            return RelevanceResult::fail('Session already started or expired');
        }

        // The user must still be the session’s organizer
        if ($this->session->organized_by !== $n->user_id) {
            return RelevanceResult::fail('User is no longer the session organizer');
        }

        return RelevanceResult::ok();
    }

    /**
     * Build the context object for templating and mail rendering.
     */
    protected function buildContext(Notification $n): NotificationContext
    {
        return new NotificationContext(
            user: $n->user,
            session: $this->session
        );
    }

    /**
     * Send via resolved channels.
     */
    protected function send(Notification $n, NotificationContext $ctx, array $channels): void
    {
        $template = (new InAppTemplateFactory())->make($n->type, [
            'session' => $ctx->session,
        ]);

        $this->channels->via($channels)->send($n, [
            'email'   => new OrganizerOfASessionMail(
                $ctx->user,
                $ctx->session
            ),
            'title'   => $template['title'],
            'message' => $template['message'],
            'link'    => $template['link'],
        ]);

        Log::info("SessionOrganizerOfASessionHandler sent notification #{$n->id} via: " . implode(', ', $channels));
    }
}
