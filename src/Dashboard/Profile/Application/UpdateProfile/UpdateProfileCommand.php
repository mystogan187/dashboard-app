<?php

namespace App\Dashboard\Profile\Application\UpdateProfile;

use App\Dashboard\Shared\Domain\Bus\Command\Command;

final class UpdateProfileCommand implements Command
{
    public function __construct(
        private readonly int $userId,
        private readonly string $name,
        private readonly string $email
    ) {}

    public function userId(): int
    {
        return $this->userId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }
}
