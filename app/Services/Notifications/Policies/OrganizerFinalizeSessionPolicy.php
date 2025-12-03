<?php

namespace App\Services\Notifications\Policies;

use App\Models\User;
use App\Enums\NotificationType;

class OrganizerFinalizeSessionPolicy extends BaseNotificationPolicy implements NotificationChannelPolicyInterface
{
    public function resolveChannels(User $user, NotificationType $type): array
    {
        $channels = ['in_app'];

        if ($this->allowsExternalNotifications($user)) {
            $channels[] = 'email';
            $channels[] = 'push';
        }

        return $channels;
    }
}
