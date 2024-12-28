<?php

namespace App\Dashboard\User\Application\Create;


use App\Dashboard\User\Domain\Entity\User;
use App\Dashboard\User\Domain\Infrastructure\UserRepository;
use App\Dashboard\User\Domain\ValueObjects\UserEmail;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class CreateUserHandler
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}

    public function __invoke(CreateUserCommand $command): void
    {
        $email = UserEmail::from($command->email());

        if ($this->repository->findByEmail($email) !== null) {
            throw new \DomainException('Email already exists');
        }

        $user = User::create(
            $command->name(),
            $email,
            $command->roles(),
            $this->passwordHasher->hashPassword(
                User::create(
                    $command->name(),
                    $email,
                    $command->roles(),
                    $command->plainPassword()
                ),
                $command->plainPassword()
            )
        );

        $this->repository->save($user);
    }
}