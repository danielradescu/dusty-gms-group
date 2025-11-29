<?php

namespace App\Services\Notifications\Policies;

use App\Models\NotificationSubscription;
use App\Models\User;
use App\Enums\NotificationType;
use App\Enums\NotificationSubscriptionType;

class SessionCreatedPolicy extends BaseNotificationPolicy implements NotificationChannelPolicyInterface
{
    public function resolveChannels(User $user, NotificationType $type): array
    {
        $channels = ['in_app']; // always include in-app

        // Add email if user is subscribed and verified
        if ($this->allowsExternalNotifications($user, NotificationSubscriptionType::NEW_GAME_SESSION)) {
            if (NotificationSubscription::isUserSubscribed($user->id, NotificationSubscriptionType::NEW_GAME_SESSION)) {
                $channels[] = 'email';
                $channels[] = 'push';
            }
        }

        return $channels;
    }
}
