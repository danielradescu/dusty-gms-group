<?php

namespace App\Services\Notifications\Handlers;

use App\Enums\GameSessionStatus;
use App\Enums\NotificationStatus;
use App\Mail\GameSessionOrganizerUpdateMail;
use App\Models\{GameSession, Notification, Comment};
use App\Services\Notifications\{NotificationContext, InAppTemplateFactory, Support\RelevanceResult};
use Illuminate\Support\Facades\Log;

class SessionOrganizerMessageHandler extends NotificationHandlerBase
{
    protected ?GameSession $session = null;
    protected ?Comment $comment = null;

    /**
     * Check if the notification is still relevant.
     */
    protected function isStillRelevant(Notification $n): RelevanceResult
    {
        $this->session = GameSession::find($n->data['session_id'] ?? null);
        $this->comment = Comment::find($n->data['comment_id'] ?? null);

        if (! $this->session) {
            return RelevanceResult::fail('Session not found');
        }

        // The session must start in the future
        if ($this->session->start_at->isPast()) {
            return RelevanceResult::fail('Session already started.');
        }

        if (! $this->comment) {
            return RelevanceResult::fail('Comment announcement not found');
        }

        // Only if session exists and was confirmed (cannot edit after confirmed, only send announcements)
        if ($this->session->status !== GameSessionStatus::CONFIRMED_BY_ORGANIZER) {
            return RelevanceResult::fail('Session not in status confirmed by organizer.');
        }

        // Don’t send to the organizer themselves
        if ($this->comment->user_id === $n->user->id) {
            return RelevanceResult::fail('Skip the owner of the comment.');
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
            session: $this->session,
            comment: $this->comment
        );
    }

    /**
     * Send the notification via resolved channels.
     */
    protected function send(Notification $n, NotificationContext $ctx, array $channels): void
    {
        $template = (new InAppTemplateFactory())->make($n->type, [
            'session' => $ctx->session,
            'comment' => $ctx->comment,
        ]);

        $this->channels->via($channels)->send($n, [
            'email'   => new GameSessionOrganizerUpdateMail(
                $ctx->user,
                $ctx->session,
                $ctx->comment->body
            ),
            'title'   => $template['title'],
            'message' => $template['message'],
            'link'    => $template['link'],
        ]);

        Log::info("SessionOrganizerMessageHandler sent notification #{$n->id} via: " . implode(', ', $channels));
    }
}
