<?php

declare(strict_types=1);

namespace App\Dashboard\Settings\Domain;

use App\Dashboard\Settings\Domain\ValueObjects\UserId;
use App\Dashboard\Settings\Domain\ValueObjects\UserPreferences;

final class Settings
{
    public function __construct(
        private readonly UserId $userId,
        private readonly UserPreferences $preferences
    ) {
    }

    public static function create(UserId $userId, UserPreferences $preferences): self
    {
        return new self($userId, $preferences);
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function preferences(): UserPreferences
    {
        return $this->preferences;
    }
}