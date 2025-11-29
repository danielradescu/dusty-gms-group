<?php

namespace App\Services\Notifications\Policies;

use App\Enums\NotificationType;
use App\Models\User;

class SessionReminderPolicy extends BaseNotificationPolicy implements NotificationChannelPolicyInterface
{
    public function resolveChannels(User $user, NotificationType $type): array
    {
        $channels = ['in_app'];

        // Reminder should also go via email/push if user allows
        if ($this->allowsExternalNotifications($user)) {
            $channels[] = 'email';
            $channels[] = 'push';
        }

        return $channels;
    }
}
