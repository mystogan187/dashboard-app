<?php

namespace App\Dashboard\User\Application\Create;

use App\Dashboard\Shared\Domain\Bus\Command\Command;

final class CreateUserCommand implements Command
{
    public function __construct(
        private readonly string $name,
        private readonly string $email,
        private readonly array $roles,
        private readonly string $plainPassword
    ) {}

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

    public function plainPassword(): string
    {
        return $this->plainPassword;
    }
}