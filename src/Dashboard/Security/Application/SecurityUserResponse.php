<?php

namespace App\Dashboard\Security\Application;

use App\Dashboard\User\Domain\Entity\User;

final class SecurityUserResponse
{
    private function __construct(
        public readonly int $id,
        public readonly string $email,
        public readonly string $name,
        public readonly array $roles,
        public readonly ?string $profilePhoto
    ) {}

    public static function fromUser(User $user): self
    {
        return new self(
            $user->id()->value(),
            $user->email()->value(),
            $user->name(),
            $user->getRoles(),
            $user->profilePhoto()
        );
    }
}