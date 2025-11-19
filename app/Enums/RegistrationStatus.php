<?php

namespace App\Enums;

enum RegistrationStatus: int
{
    case RemindMe2Days = 0;
    case Confirmed = 1;
    case Declined = 2;
    case OpenPosition = 3;


    public function label(): string
    {
        return match($this) {
            self::RemindMe2Days => 'Interested, remind me 2 days prior the session start.',
            self::OpenPosition => 'Interested, let me know when a new position is opened.',
            self::Confirmed => 'Confirmed',
            self::Declined => 'Declined',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::RemindMe2Days, self::OpenPosition => 'text-blue-500',
            self::Confirmed => 'text-green-500',
            self::Declined => 'text-red-500',
        };
    }
}
