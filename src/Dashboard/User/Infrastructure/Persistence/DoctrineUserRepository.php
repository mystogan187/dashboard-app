<?php

namespace App\Dashboard\User\Infrastructure\Persistence;

use App\Dashboard\User\Domain\Entity\User;
use App\Dashboard\User\Domain\Infrastructure\UserRepository;
use App\Dashboard\User\Domain\ValueObjects\UserEmail;
use App\Dashboard\User\Domain\ValueObjects\UserId;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineUserRepository implements UserRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function findAll(): array
    {
        return $this->entityManager->getRepository(User::class)->findAll();
    }

    public function findById(UserId $id): ?User
    {
        return $this->entityManager->getRepository(User::class)->find($id->value());
    }

    public function findByEmail(UserEmail $email): ?User
    {
        return $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $email->value()]);
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function delete(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}