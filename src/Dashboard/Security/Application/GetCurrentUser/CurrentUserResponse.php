<?php

namespace App\Dashboard\Security\Application\GetCurrentUser;

final class CurrentUserResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $email,
        public readonly string $name,
        public readonly array $roles,
        public readonly ?string $profilePhoto
    ) {}
}