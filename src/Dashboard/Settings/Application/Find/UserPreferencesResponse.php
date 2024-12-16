<?php

declare(strict_types=1);

namespace App\Dashboard\Settings\Application\Find;

final class UserPreferencesResponse
{
    public function __construct(
        private readonly array $preferences
    ) {}

    public static function fromArray(array $preferences): self
    {
        return new self($preferences);
    }

    public function toArray(): array
    {
        return $this->preferences;
    }
}