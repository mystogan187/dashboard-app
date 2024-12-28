<?php

namespace App\Dashboard\Profile\Infrastructure\Persistence;

use App\Dashboard\Profile\Domain\Entity\Profile;
use App\Dashboard\Profile\Domain\Infrastructure\ProfileRepository;
use App\Dashboard\Profile\Domain\ValueObjects\Email;
use App\Dashboard\User\Domain\ValueObjects\UserId;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class DoctrineProfileRepository implements ProfileRepository
{
    private EntityRepository $repository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
        $this->repository = $entityManager->getRepository(Profile::class);
    }

    public function findByEmail(Email $email): ?Profile
    {
        return $this->repository->findOneBy(['email' => $email->value()]);
    }

    public function findById(UserId $id): ?Profile
    {
        return $this->repository->find($id->value());
    }

    public function save(Profile $profile): void
    {
        $this->entityManager->persist($profile);
        $this->entityManager->flush();
    }
}