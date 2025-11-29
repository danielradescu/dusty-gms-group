<?php

namespace App\Services\Notifications;

use App\Enums\NotificationType;

class InAppTemplateFactory
{
    public function make(NotificationType $type, array $context): array
    {
        $session = $context['session'] ?? null;

        return match ($type) {
            NotificationType::SESSION_CREATED => [
                'title' => '🎲 New Game Session',
                'message' => sprintf('"%s" is open for %s!',
                    $session->name,
                    $session->start_at?->format('l, M j')
                ),
                'link' => $session ? route('game-session.interaction.show', $session->uuid) : null,
            ],
            NotificationType::SESSION_CONFIRMED => [
                'title' => '✅ Game Session Confirmed!',
                'message' => sprintf(
                    'Your session "%s" has been confirmed and is ready to play.',
                    $session->name
                ),
                'link' => $session ? route('game-session.interaction.show', $session->uuid) : null,
            ],
            NotificationType::SESSION_CANCELED => [
                'title' => '❌ Game Session Canceled',
                'message' => sprintf(
                    'Your session "%s" has been canceled by the organizer.',
                    $session->name
                ),
                'link' => $session ? route('game-session.interaction.show', $session->uuid) : null,
            ],
            NotificationType::SESSION_ORGANIZER_MESSAGE => [
                'title' => '📢 Organizer Update',
                'message' => sprintf(
                    'The organizer posted an update for "%s": "%s"',
                    $session->name,
                    str($context['comment']->body)->limit(80)
                ),
                'link' => $session ? route('game-session.interaction.show', $session->uuid) . '#post-comment' : null,
            ],
            default => [
                'title' => 'Notification',
                'message' => 'You have a new update.',
                'link' => null,
            ],
        };
    }
}
