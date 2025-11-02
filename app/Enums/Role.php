<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'Administrator';
    case ORGANIZER = 'Organizer';
    case PARTICIPANT = 'Participant';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::ORGANIZER => 'Organizer',
            self::PARTICIPANT => 'Participant',
        };
    }
}
