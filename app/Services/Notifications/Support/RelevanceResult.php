<?php

namespace App\Services\Notifications\Support;

class RelevanceResult
{
    public function __construct(
        public bool $isRelevant,
        public ?string $reason = null,
    ) {}

    public static function ok(): self
    {
        return new self(true);
    }

    public static function fail(string $reason): self
    {
        return new self(false, $reason);
    }
}
