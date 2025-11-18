<?php

namespace App\Enums;

enum Role: int
{
    case Admin = 1;
    case Organizer = 2;
    case Participant = 3;

    public static function fromInt(int $value): self
    {
        return match ($value) {
            1 => self::Admin,
            2 => self::Organizer,
            3 => self::Participant,
            default => throw new \InvalidArgumentException("Invalid role value: $value"),
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Organizer => 'Organizer',
            self::Participant => 'Participant',
        };
    }
}
