<?php

namespace App\Dashboard\Security\Application\GetCurrentUser;

use App\Dashboard\Shared\Domain\Bus\Query\Query;

final class GetCurrentUserQuery implements Query
{
    public function __construct(
        private readonly string $userIdentifier
    ) {}

    public function userIdentifier(): string
    {
        return $this->userIdentifier;
    }
}