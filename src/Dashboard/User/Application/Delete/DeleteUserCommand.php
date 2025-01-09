<?php

namespace App\Dashboard\User\Application\Delete;

use App\Dashboard\Shared\Domain\Bus\Command\Command;
use App\Dashboard\User\Domain\ValueObjects\UserId;

final class DeleteUserCommand implements Command
{
    public function __construct(
        private readonly int $id,
        private readonly int $currentUserId
    ) {}

    public function id(): UserId
    {
        return UserId::from($this->id);
    }

    public function currentUserId(): UserId
    {
        return UserId::from($this->currentUserId);
    }
}