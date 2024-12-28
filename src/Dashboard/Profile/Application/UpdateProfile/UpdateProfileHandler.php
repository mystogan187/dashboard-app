<?php

namespace App\Dashboard\Profile\Application\UpdateProfile;

use App\Dashboard\Profile\Application\ProfileResponse;
use App\Dashboard\User\Domain\Infrastructure\UserRepository;
use App\Dashboard\User\Domain\ValueObjects\UserId;
use App\Dashboard\User\Domain\ValueObjects\UserEmail;

final class UpdateProfileHandler
{
    public function __construct(
        private readonly UserRepository $repository
    ) {}

    public function __invoke(UpdateProfileCommand $command): ProfileResponse
    {
        $email = UserEmail::from($command->email());

        $existingUser = $this->repository->findByEmail($email);
        if ($existingUser !== null && $existingUser->id()->value() !== $command->userId()) {
            throw new \DomainException('Email already in use');
        }

        $user = $this->repository->findById(UserId::from($command->userId()));
        if ($user === null) {
            throw new \DomainException('User not found');
        }

        $user->update($command->name(), $email, $user->getRoles());
        $this->repository->save($user);

        return ProfileResponse::fromUser($user);
    }
}