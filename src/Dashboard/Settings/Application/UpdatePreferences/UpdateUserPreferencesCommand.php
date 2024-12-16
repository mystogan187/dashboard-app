<?php

declare(strict_types=1);

namespace App\Dashboard\Settings\Application\UpdatePreferences;

use App\Shared\Domain\Bus\Command\Command;

final class UpdateUserPreferencesCommand implements Command
{
    public function __construct(
        public readonly int $userId,
        public readonly array $preferences
    ) {}
}