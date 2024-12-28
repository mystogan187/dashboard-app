<?php

namespace App\Dashboard\Profile\Application\ChangePassword;

final class ChangePasswordCommand
{
    public function __construct(
        private readonly int $userId,
        private readonly string $currentPassword,
        private readonly string $newPassword
    ) {}

    public function userId(): int
    {
        return $this->userId;
    }

    public function currentPassword(): string
    {
        return $this->currentPassword;
    }

    public function newPassword(): string
    {
        return $this->newPassword;
    }
}