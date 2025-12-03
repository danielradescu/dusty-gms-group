<?php

namespace App\Services\Notifications\Handlers;

use App\Enums\GameSessionStatus;
use App\Models\{GameSession, Notification};
use App\Services\Notifications\{
    NotificationContext,
    InAppTemplateFactory
};
use App\Services\Notifications\Support\RelevanceResult;
use Illuminate\Support\Facades\Log;

class AdminFinalizeSessionHandler extends NotificationHandlerBase
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

        // Must have started at least 3 days ago
        if ($this->session->start_at->isFuture()) {
            return RelevanceResult::fail('Session has not started yet');
        }

        // Only relevant if still not finalized
        if (! in_array(
            $this->session->status,
            [GameSessionStatus::RECRUITING_PARTICIPANTS, GameSessionStatus::CONFIRMED_BY_ORGANIZER],
            true
        )) {
            return RelevanceResult::fail('Session already finalized or cancelled');
        }

        // Ensure this user is still admin
        if (! $n->user->isAdmin()) {
            return RelevanceResult::fail('User is not an administrator any longer');
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
        // Force in-app only, even if policy allows more
        $channels = ['in_app'];

        $template = (new InAppTemplateFactory())->make($n->type, [
            'session' => $ctx->session,
        ]);

        $this->channels->via($channels)->send($n, [
            'title'   => $template['title'],
            'message' => $template['message'],
            'link'    => $template['link'],
        ]);

        Log::info("AdminFinalizeSessionHandler sent notification #{$n->id} (in-app only).");
    }
}
