<?php

namespace App\Dashboard\Security\Application\GetCurrentUser;

use App\Dashboard\User\Domain\Infrastructure\UserRepository;
use App\Dashboard\User\Domain\ValueObjects\UserEmail;

final class GetCurrentUserHandler
{
    public function __construct(
        private readonly UserRepository $repository
    ) {}

    public function __invoke(GetCurrentUserQuery $query): CurrentUserResponse
    {
        $user = $this->repository->findByEmail(UserEmail::from($query->userIdentifier()));

        if ($user === null) {
            throw new \DomainException('User not found');
        }

        return new CurrentUserResponse(
            $user->id()->value(),
            $user->email()->value(),
            $user->name(),
            $user->getRoles(),
            $user->profilePhoto()
        );
    }
}