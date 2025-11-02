<?php

namespace App\Enums;

enum RegistrationStatus: int
{
    case Interested = 0;
    case Confirmed = 1;
    case Declined = 2;


    public function label(): string
    {
        return match($this) {
            self::Interested => 'Interested',
            self::Confirmed => 'Confirmed',
            self::Declined => 'Declined',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Interested => 'text-blue-500',
            self::Confirmed => 'text-green-500',
            self::Declined => 'text-red-500',
        };
    }
}
