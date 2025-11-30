<?php

namespace App\Services\Notifications\Policies;

use App\Enums\NotificationType;
use App\Models\User;

class SessionOrganizerNewCommentPolicy extends BaseNotificationPolicy implements NotificationChannelPolicyInterface
{
    public function resolveChannels(User $user, NotificationType $type): array
    {
        $channels = ['in_app']; // Always deliver in-app

        // Optional email/push if user allows session updates
        if ($this->allowsExternalNotifications($user)) {
            $channels[] = 'email';
            $channels[] = 'push';
        }

        return $channels;
    }
}
