<?php

namespace App\Services\Notifications;

use App\Enums\NotificationType;

class InAppTemplateFactory
{
    public function make(NotificationType $type, array $context): array
    {
        return match ($type) {
            NotificationType::SESSION_CREATED => [
                'title' => '🎲 New Game Session',
                'message' => sprintf('"%s" is open for %s!',
                    $context['session']->name,
                    $context['session']->start_at?->format('l, M j')
                ),
            ],
            default => [
                'title' => 'Notification',
                'message' => 'You have a new update.',
            ],
        };
    }
}
