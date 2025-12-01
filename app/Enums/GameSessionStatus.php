<?php

namespace App\Enums;

enum GameSessionStatus: int
{
    case RECRUITING_PARTICIPANTS = 1;
    case CONFIRMED_BY_ORGANIZER = 2;
    case CANCELLED = 3;
    case FAILED = 4;
    case SUCCEEDED = 5;

    public function label(): string
    {
        return match ($this) {
            self::RECRUITING_PARTICIPANTS => 'Recruiting participants',
            self::CONFIRMED_BY_ORGANIZER => 'Confirmed by organizer',
            self::CANCELLED => 'Cancelled',
            self::FAILED => '👎 Failed 👎',
            self::SUCCEEDED => '👍 Succeeded 👍',

        };
    }
}
