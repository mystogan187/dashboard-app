<?php

namespace App\Dashboard\User\Application\Get;

use App\Dashboard\Shared\Domain\Bus\Query\Query;
use App\Dashboard\User\Domain\ValueObjects\UserId;

final class GetUserQuery implements Query
{
    public function __construct(
        private readonly int $id
    ) {}

    public function id(): UserId
    {
        return UserId::from($this->id);
    }
}