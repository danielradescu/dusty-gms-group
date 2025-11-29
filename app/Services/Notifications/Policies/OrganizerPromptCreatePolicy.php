<?php

namespace App\Services\Notifications\Policies;

use App\Enums\NotificationType;
use App\Models\User;

class OrganizerPromptCreatePolicy extends BaseNotificationPolicy implements NotificationChannelPolicyInterface
{
    public function resolveChannels(User $user, NotificationType $type): array
    {
        $channels = ['in_app']; // Always visible in-app

        if ($this->allowsExternalNotifications($user)) {
            $channels[] = 'email';
            $channels[] = 'push';
        }

        return $channels;
    }
}
