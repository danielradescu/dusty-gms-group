<?php

namespace App\Services\Notifications;

use App\Enums\NotificationType;
use App\Services\Notifications\Handlers\{
    SessionCreatedHandler
};

class NotificationHandlerFactory
{
    public function make(NotificationType $type): ?Handlers\NotificationHandlerBase
    {
        return match ($type) {
            NotificationType::SESSION_CREATED => new SessionCreatedHandler(),
            default => null,
        };
    }
}
