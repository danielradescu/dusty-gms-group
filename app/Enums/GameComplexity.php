<?php

namespace App\Enums;

enum GameComplexity: int
{
    case Casual = 1;
    case Flexible = 2;
    case Competitive = 3;

    public function label(): string
    {
        return match ($this) {
            self::Casual => 'Casual',
            self::Flexible => 'Flexible',
            self::Competitive => 'Competitive',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Casual => 'For new or occasional players. Light games (e.g., Gateway games).',
            self::Flexible => 'Mixed sessions. Games picked depending on the players present.',
            self::Competitive => 'For experienced players. Familiarity with rules is expected.',
        };
    }

    public function getNumber(): string
    {
        return match ($this) {
            self::Casual => 1,
            self::Flexible => 3,
            self::Competitive => 5,
        };
    }
}
