<?php

namespace App\Dashboard\Profile\Application\ChangePassword;

use App\Dashboard\Profile\Application\ProfileResponse;
use App\Dashboard\User\Domain\Infrastructure\UserRepository;
use App\Dashboard\User\Domain\ValueObjects\UserId;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ChangePasswordHandler
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}

    public function __invoke(ChangePasswordCommand $command): ProfileResponse
    {
        $user = $this->repository->findById(UserId::from($command->userId()));
        if ($user === null) {
            throw new \DomainException('User not found');
        }

        if (!$this->passwordHasher->isPasswordValid($user, $command->currentPassword())) {
            throw new \DomainException('Current password is invalid');
        }

        if (strlen($command->newPassword()) < 6) {
            throw new \DomainException('Password must be at least 6 characters long');
        }

        $hashedPassword = $this->passwordHasher->hashPassword($user, $command->newPassword());
        $user->updatePassword($hashedPassword);
        $this->repository->save($user);

        return ProfileResponse::fromUser($user);
    }
}