<?php

namespace App\Services\Notifications;

use App\Enums\NotificationType;
use App\Services\Notifications\Handlers\{OrganizerPromptCreateHandler,
    SessionAutoJoinedHandler,
    SessionCanceledHandler,
    SessionConfirmedHandler,
    SessionCreatedHandler,
    SessionOpenSlotAvailableHandler,
    SessionOrganizerMessageHandler,
    SessionOrganizerNewCommentHandler,
    SessionReminderHandler};

class NotificationHandlerFactory
{
    public function make(NotificationType $type): ?Handlers\NotificationHandlerBase
    {
        return match ($type) {
            NotificationType::SESSION_CREATED           => app(SessionCreatedHandler::class),
            NotificationType::SESSION_CONFIRMED         => app(SessionConfirmedHandler::class),
            NotificationType::SESSION_CANCELED          => app(SessionCanceledHandler::class),
            NotificationType::SESSION_ORGANIZER_MESSAGE => app(SessionOrganizerMessageHandler::class),
            NotificationType::SESSION_REMINDER          => app(SessionReminderHandler::class),
            NotificationType::SESSION_AUTO_JOINED       => app(SessionAutoJoinedHandler::class),
            NotificationType::OPEN_SLOT_AVAILABLE       => app(SessionOpenSlotAvailableHandler::class),
            NotificationType::ORGANIZER_PROMPT_CREATE   => app(OrganizerPromptCreateHandler::class),
            NotificationType::NEW_COMMENT               => app(SessionOrganizerNewCommentHandler::class),
            default => null,
        };
    }
}
