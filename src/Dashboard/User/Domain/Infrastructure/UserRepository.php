<?php

namespace App\Dashboard\User\Domain\Infrastructure;

use App\Dashboard\User\Domain\Entity\User;
use App\Dashboard\User\Domain\ValueObjects\UserEmail;
use App\Dashboard\User\Domain\ValueObjects\UserId;

interface UserRepository
{
    public function findAll(): array;
    public function findById(UserId $id): ?User;
    public function findByEmail(UserEmail $email): ?User;
    public function save(User $user): void;
    public function delete(User $user): void;
}