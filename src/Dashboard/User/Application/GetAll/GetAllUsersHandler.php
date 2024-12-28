<?php

namespace App\Dashboard\User\Application\GetAll;

use App\Dashboard\User\Domain\Infrastructure\UserRepository;
use App\Dashboard\User\Application\UserResponse;

final class GetAllUsersHandler
{
    public function __construct(
        private readonly UserRepository $repository
    ) {}

    public function __invoke(GetAllUsersQuery $query): array
    {
        $users = $this->repository->findAll();
        return array_map(fn($user) => UserResponse::fromUser($user), $users);
    }
}