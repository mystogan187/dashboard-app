<?php

namespace App\Dashboard\User\Application\Update;

use App\Dashboard\User\Domain\Infrastructure\UserRepository;
use App\Dashboard\User\Domain\ValueObjects\UserEmail;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UpdateUserHandler
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}

    public function __invoke(UpdateUserCommand $command): void
    {
        $user = $this->repository->findById($command->id());
        if ($user === null) {
            throw new \DomainException('User not found');
        }

        $email = UserEmail::from($command->email());

        $existingUser = $this->repository->findByEmail($email);
        if ($existingUser !== null && $existingUser->id()->value() !== $command->id()->value()) {
            throw new \DomainException('Email already in use');
        }

        $user->update($command->name(), $email, $command->roles());

        $hashedPassword = $this->passwordHasher->hashPassword($user, $command->password());
        $user->updatePassword($hashedPassword);

        $this->repository->save($user);
    }
}