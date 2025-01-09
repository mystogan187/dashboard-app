<?php

namespace App\Dashboard\Profile\Application\ChangePassword;

use App\Dashboard\Shared\Domain\Bus\Command\Command;

final class ChangePasswordCommand implements Command
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