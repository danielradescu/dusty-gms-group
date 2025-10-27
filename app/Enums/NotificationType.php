<?php

namespace App\Enums;

enum NotificationType: int
{
    case NEW = 1;
    case REMINDER = 2;
    case POSITION_OPEN = 3;
    case CANCELLED = 4;
    case CUSTOM = 5;

    public function label(): string
    {
        return match ($this) {
            self::REMINDER => 'Reminder',
            self::NEW => 'New',
            self::POSITION_OPEN => 'Open',
            self::CANCELLED => 'Cancelled',
            self::CUSTOM => 'Custom',
        };
    }
}
