<?php

namespace App\Dashboard\User\Application\Delete;

use App\Dashboard\User\Domain\Infrastructure\UserRepository;

final class DeleteUserHandler
{
    public function __construct(
        private readonly UserRepository $repository
    ) {}

    public function __invoke(DeleteUserCommand $command): void
    {
        $user = $this->repository->findById($command->id());
        if ($user === null) {
            throw new \DomainException('User not found');
        }

        if ($user->id()->value() === $command->currentUserId()->value()) {
            throw new \DomainException('Cannot delete your own user');
        }

        $this->repository->delete($user);
    }
}