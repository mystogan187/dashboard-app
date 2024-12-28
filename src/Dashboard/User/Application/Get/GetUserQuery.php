<?php

namespace App\Dashboard\User\Application\Get;

use App\Dashboard\User\Domain\ValueObjects\UserId;

final class GetUserQuery
{
    public function __construct(
        private readonly int $id
    ) {}

    public function id(): UserId
    {
        return UserId::from($this->id);
    }
}