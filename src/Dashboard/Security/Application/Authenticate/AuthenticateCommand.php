<?php

namespace App\Dashboard\Security\Application\Authenticate;

use App\Dashboard\Shared\Domain\Bus\Command\Command;

final class AuthenticateCommand implements Command
{
    public function __construct(
        private readonly string $email,
        private readonly string $password
    ) {}

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }
}