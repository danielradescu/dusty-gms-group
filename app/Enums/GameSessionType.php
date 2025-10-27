<?php

namespace App\Enums;

enum GameSessionType: int
{
    case ACTIVE = 1;
    case FAILED = 2;
    case SUCCEEDED = 3;
    case CANCELLED = 4;
    case PENDING = 5;

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::FAILED => 'Failed',
            self::SUCCEEDED => 'Succeeded',
            self::CANCELLED => 'Cancelled',
            self::PENDING => 'Pending',
        };
    }
}
