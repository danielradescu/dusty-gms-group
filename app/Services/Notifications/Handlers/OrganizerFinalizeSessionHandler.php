<?php

namespace App\Services\Notifications\Handlers;

use App\Enums\GameSessionStatus;
use App\Mail\OrganizerFinalizeGameSessionMail;
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

class OrganizerFinalizeSessionHandler extends NotificationHandlerBase
{
    protected ?GameSession $session = null;

    /**
     * Determine if the notification is still relevant.
     */
    protected function isStillRelevant(Notification $n): RelevanceResult
    {
        $this->session = GameSession::find($n->data['session_id'] ?? null);

        if (! $this->session) {
            return RelevanceResult::fail('Session not found');
        }

        // Must have started already
        if ($this->session->start_at->isFuture()) {
            return RelevanceResult::fail('Session has not started yet');
        }

        // Only relevant for recruiting/confirmed sessions that need closing
        if (! in_array(
            $this->session->status,
            [GameSessionStatus::RECRUITING_PARTICIPANTS, GameSessionStatus::CONFIRMED_BY_ORGANIZER],
            true
        )) {
            return RelevanceResult::fail('Session already finalized or cancelled.');
        }

        // Ensure this user is the organizer
        if ($this->session->organized_by !== $n->user_id) {
            return RelevanceResult::fail('User is not the organizer any longer');
        }

        return RelevanceResult::ok();
    }

    /**
     * Build the context.
     */
    protected function buildContext(Notification $n): NotificationContext
    {
        return new NotificationContext(
            user: $n->user,
            session: $this->session
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
            'email'   => new OrganizerFinalizeGameSessionMail($ctx->user, $ctx->session),
            'title'   => $template['title'],
            'message' => $template['message'],
            'link'    => $template['link'],
        ]);

        Log::info("OrganizerFinalizeSessionHandler sent notification #{$n->id} via: " . implode(', ', $channels));
    }
}
