<?php

namespace App\Services\Notifications;

use App\Enums\NotificationType;
use App\Models\User;
use App\Services\Notifications\Policies\{NotificationChannelPolicyInterface,
    SessionCanceledPolicy,
    SessionConfirmedPolicy,
    SessionCreatedPolicy,
    SessionOrganizerMessagePolicy};

class NotificationPolicyResolver
{
    protected array $map = [
        NotificationType::SESSION_CREATED->value => SessionCreatedPolicy::class,
        NotificationType::SESSION_CONFIRMED->value => SessionConfirmedPolicy::class,
        NotificationType::SESSION_CANCELED->value  => SessionCanceledPolicy::class,
        NotificationType::SESSION_ORGANIZER_MESSAGE->value  => SessionOrganizerMessagePolicy::class,
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
