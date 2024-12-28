<?php

namespace App\Dashboard\User\Application\Get;

use App\Dashboard\User\Domain\Infrastructure\UserRepository;
use App\Dashboard\User\Application\UserResponse;

final class GetUserHandler
{
    public function __construct(
        private readonly UserRepository $repository
    ) {}

    public function __invoke(GetUserQuery $query): UserResponse
    {
        $user = $this->repository->findById($query->id());
        if ($user === null) {
            throw new \DomainException('User not found');
        }

        return UserResponse::fromUser($user);
    }
}