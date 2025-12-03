<?php

namespace App\Services\Notifications;

use App\Enums\NotificationType;
use App\Models\User;
use App\Services\Notifications\Handlers\AdminFinalizeSessionHandler;
use App\Services\Notifications\Handlers\OrganizerFinalizeSessionHandler;
use App\Services\Notifications\Policies\{AdminFinalizeSessionPolicy,
    NotificationChannelPolicyInterface,
    OrganizerFinalizeSessionPolicy,
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
        NotificationType::ORGANIZER_FINALIZE_SESSION->value => OrganizerFinalizeSessionPolicy::class,
        NotificationType::ADMIN_FINALIZE_SESSION->value => AdminFinalizeSessionPolicy::class,
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
