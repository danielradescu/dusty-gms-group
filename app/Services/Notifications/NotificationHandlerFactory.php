<?php

namespace App\Services\Notifications;

use App\Enums\NotificationType;
use App\Services\Notifications\Handlers\{SessionCanceledHandler,
    SessionConfirmedHandler,
    SessionCreatedHandler,
    SessionOrganizerMessageHandler,
    SessionReminderHandler};

class NotificationHandlerFactory
{
    public function make(NotificationType $type): ?Handlers\NotificationHandlerBase
    {
        return match ($type) {
            NotificationType::SESSION_CREATED   => app(SessionCreatedHandler::class),
            NotificationType::SESSION_CONFIRMED => app(SessionConfirmedHandler::class),
            NotificationType::SESSION_CANCELED  => app(SessionCanceledHandler::class),
            NotificationType::SESSION_ORGANIZER_MESSAGE => app(SessionOrganizerMessageHandler::class),
            NotificationType::SESSION_REMINDER          => app(SessionReminderHandler::class),
            default => null,
        };
    }
}
