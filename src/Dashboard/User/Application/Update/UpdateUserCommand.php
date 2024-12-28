<?php

namespace App\Dashboard\User\Application\Update;

use App\Dashboard\User\Domain\ValueObjects\UserId;

final class UpdateUserCommand
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly string $email,
        private readonly array $roles,
        private readonly ?string $password = null
    ) {}

    public function id(): UserId
    {
        return UserId::from($this->id);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function roles(): array
    {
        return $this->roles;
    }

    public function password(): ?string
    {
        return $this->password;
    }
}