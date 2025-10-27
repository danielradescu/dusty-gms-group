<?php

namespace App\Enums;

enum NotificationSubscriptionType: int
{
    case NEW = 1;
    case REMINDER_1DAY = 2;
    case POSITION_OPEN = 3;
    case CANCELLED = 4;
    case UPDATED = 5;

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'Notify me about any NEW game sessions',
            self::REMINDER_1DAY => 'Remind me about boardgames session I subscribed 1 DAY BEFORE.',
            self::POSITION_OPEN => 'Notify me about OPEN POSITION(s) at boardgames session I subscribed.',
            self::CANCELLED => 'Notify me about CANCELLED game sessions I subscribed.',
            self::UPDATED => 'Notify me about CHANGES for the game sessions I subscribed',
        };
    }
}
