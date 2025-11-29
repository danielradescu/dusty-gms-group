<?php

namespace App\Services\Notifications\Policies;

use App\Models\User;
use App\Enums\NotificationType;

interface NotificationChannelPolicyInterface
{
    /**
     * Return which channels this user should receive this notification on.
     *
     * @return string[] ['in_app', 'email', 'push']
     */
    public function resolveChannels(User $user, NotificationType $type): array;
}
