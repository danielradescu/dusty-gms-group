<?php

namespace App\Services\Notifications;

use App\Enums\NotificationType;
use App\Models\User;
use App\Services\Notifications\Policies\{NotificationChannelPolicyInterface,
    OrganizerOfASessionPolicy,
    OrganizerPromptCreatePolicy,
    SessionAutoJoinedPolicy,
    SessionCanceledPolicy,
    SessionConfirmedPolicy,
    SessionCreatedPolicy,
    SessionOpenSlotAvailablePolicy,
    SessionOrganizerMessagePolicy,
    SessionOrganizerNewCommentPolicy,
    SessionReminderPolicy};

class NotificationPolicyResolver
{
    protected array $map = [
        NotificationType::SESSION_CREATED->value => SessionCreatedPolicy::class,
        NotificationType::SESSION_CONFIRMED->value => SessionConfirmedPolicy::class,
        NotificationType::SESSION_CANCELED->value  => SessionCanceledPolicy::class,
        NotificationType::SESSION_ORGANIZER_MESSAGE->value  => SessionOrganizerMessagePolicy::class,
        NotificationType::SESSION_REMINDER->value  => SessionReminderPolicy::class,
        NotificationType::SESSION_AUTO_JOINED->value => SessionAutoJoinedPolicy::class,
        NotificationType::OPEN_SLOT_AVAILABLE->value => SessionOpenSlotAvailablePolicy::class,
        NotificationType::ORGANIZER_PROMPT_CREATE->value => OrganizerPromptCreatePolicy::class,
        NotificationType::NEW_COMMENT->value => SessionOrganizerNewCommentPolicy::class,
        NotificationType::ORGANIZER_OF_A_SESSION->value => OrganizerOfASessionPolicy::class,
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
