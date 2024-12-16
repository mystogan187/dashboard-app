<?php

declare(strict_types=1);

namespace App\Dashboard\Settings\Domain;

use App\Dashboard\Settings\Domain\ValueObjects\UserId;

interface SettingsRepository
{
    public function save(Settings $settings): void;
    public function findByUserId(UserId $userId): ?Settings;
}