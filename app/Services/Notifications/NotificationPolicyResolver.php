<?php

namespace App\Services\Notifications;

use App\Enums\NotificationType;
use App\Models\User;
use App\Services\Notifications\Policies\{
    NotificationChannelPolicyInterface,
    SessionCreatedPolicy,
};

class NotificationPolicyResolver
{
    protected array $map = [
        NotificationType::SESSION_CREATED->value => SessionCreatedPolicy::class,
        // ... other types
    ];

    public function resolveChannels(User $user, NotificationType $type): array
    {
        $policyClass = $this->map[$type->value] ?? null;

        if (! $policyClass) {
            // fallback: in-app only
            return ['in_app'];
        }

        /** @var NotificationChannelPolicyInterface $policy */
        $policy = app($policyClass);

        return $policy->resolveChannels($user, $type);
    }
}
