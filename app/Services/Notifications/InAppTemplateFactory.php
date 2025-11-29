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
            NotificationType::SESSION_REMINDER => [
                'title' => '⏰ Reminder: Upcoming Game Session',
                'message' => sprintf(
                    'Your session "%s" starts on %s at %s. Confirm your spot before it fills up!',
                    $session->name,
                    $session->start_at?->format('l, M j'),
                    $session->start_at?->format('H:i')
                ),
                'link' => $session ? route('game-session.interaction.show', $session->uuid) : null,
            ],
            NotificationType::SESSION_AUTO_JOINED => [
                'title' => '✅ You’ve Been Auto-Joined to a Game Session!',
                'message' => sprintf(
                    'A new session "%s" was created for %s, and you’ve been automatically added as confirmed!',
                    $session->name,
                    $context['target_date']
                        ? \Carbon\Carbon::parse($context['target_date'])->format('l, M j')
                        : $session->start_at?->format('l, M j')
                ),
                'link' => $session ? route('game-session.interaction.show', $session->uuid) : null,
            ],

            default => [
                'title' => 'Notification',
                'message' => 'You have a new update.',
                'link' => null,
            ],
        };
    }
}
