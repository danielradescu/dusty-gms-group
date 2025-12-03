<?php

namespace App\Services\Notifications\Policies;

use App\Models\User;
use App\Enums\NotificationType;

class AdminFinalizeSessionPolicy extends BaseNotificationPolicy implements NotificationChannelPolicyInterface
{
    public function resolveChannels(User $user, NotificationType $type): array
    {
        // Always in-app only — no email or push
        return ['in_app'];
    }
}
