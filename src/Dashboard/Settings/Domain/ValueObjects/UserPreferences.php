<?php

declare(strict_types=1);

namespace App\Dashboard\Settings\Domain\ValueObjects;

final class UserPreferences
{
    public function __construct(
        private readonly bool $notifications,
        private readonly bool $darkMode
    ) {
    }

    public function toArray(): array
    {
        return [
            'notifications' => $this->notifications,
            'darkMode' => $this->darkMode
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['notifications'] ?? false,
            $data['darkMode'] ?? false
        );
    }
}