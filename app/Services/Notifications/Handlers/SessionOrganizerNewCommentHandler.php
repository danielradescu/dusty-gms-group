<?php

namespace App\Services\Notifications\Handlers;

use App\Enums\{
    GameSessionStatus,
    NotificationType
};
use App\Mail\GameSessionOrganizerNewCommentMail;
use App\Models\{
    GameSession,
    Notification,
    Comment
};
use App\Services\Notifications\{
    NotificationContext,
    InAppTemplateFactory
};
use App\Services\Notifications\Support\RelevanceResult;
use Illuminate\Support\Facades\Log;

class SessionOrganizerNewCommentHandler extends NotificationHandlerBase
{
    protected ?GameSession $session = null;
    protected ?Comment $comment = null;

    /**
     * Determine if the notification is still relevant.
     */
    protected function isStillRelevant(Notification $n): RelevanceResult
    {
        $this->session = GameSession::find($n->data['session_id'] ?? null);

        if (! $this->session) {
            return RelevanceResult::fail('Session not found');
        }

        // Skip if session already started
        if ($this->session->start_at->isPast()) {
            return RelevanceResult::fail('Session already started');
        }

        // Skip if session not accepting participants anymore
        if (! $this->session->canChangeStatus()) {
            return RelevanceResult::fail('Session not open for participant interaction');
        }

        return RelevanceResult::ok();
    }

    /**
     * Build the context object for templating and delivery.
     */
    protected function buildContext(Notification $n): NotificationContext
    {
        return new NotificationContext(
            user: $n->user,
            session: $this->session,
//            comment: $this->comment ?? (Comment::find($n->data['comment_id'] ?? null)),
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
            'email'   => new GameSessionOrganizerNewCommentMail(
                $ctx->user,
                $ctx->session,
            ),
            'title'   => $template['title'],
            'message' => $template['message'],
            'link'    => $template['link'],
        ]);

        Log::info("SessionOrganizerNewCommentHandler sent notification #{$n->id} via: " . implode(', ', $channels));
    }
}
