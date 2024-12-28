<?php

namespace App\Dashboard\Security\Application\Authenticate;

final class AuthenticationResponse
{
    public function __construct(
        public readonly string $token,
        public readonly string $email,
        public readonly string $name,
        public readonly array $roles
    ) {}
}