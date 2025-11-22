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
            self::RemindMe2Days => 'Interested — notify me 2 days before the session.',
            self::OpenPosition  => 'Interested — notify me when a seat becomes available.',
            self::Confirmed     => 'Confirmed — I’m attending this session.',
            self::Declined      => 'Declined — I won’t be joining this session.',
        };
    }
}
