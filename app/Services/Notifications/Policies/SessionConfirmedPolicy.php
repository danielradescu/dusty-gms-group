<?php

namespace App\Services\Notifications\Policies;

use App\Models\NotificationSubscription;
use App\Models\Registration;
use App\Models\User;
use App\Enums\NotificationType;
use App\Enums\NotificationSubscriptionType;
use Illuminate\Support\Facades\Log;

class SessionConfirmedPolicy extends BaseNotificationPolicy implements NotificationChannelPolicyInterface
{
    public function resolveChannels(User $user, NotificationType $type): array
    {
        $channels = ['in_app'];

        // Add email if user is subscribed and verified
        if ($this->allowsExternalNotifications($user)) {
            $channels[] = 'email';
            $channels[] = 'push';
        }

        return $channels;
    }
}
