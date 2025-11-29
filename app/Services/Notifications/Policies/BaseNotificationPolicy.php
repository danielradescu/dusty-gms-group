<?php

namespace App\Services\Notifications\Policies;
use App\Enums\NotificationSubscriptionType;
use App\Models\User;

abstract class BaseNotificationPolicy implements NotificationChannelPolicyInterface
{
    protected function allowsExternalNotifications(User $user): bool
    {
        return $user->hasVerifiedEmail() &&
            $user->hasExternalNotifications();
    }
}
