<?php

namespace App\Dashboard\Profile\Domain\Infrastructure;


use App\Dashboard\Profile\Domain\Entity\Profile;
use App\Dashboard\Profile\Domain\ValueObjects\Email;
use App\Dashboard\User\Domain\ValueObjects\UserId;

interface ProfileRepository
{
    public function findByEmail(Email $email): ?Profile;
    public function findById(UserId $id): ?Profile;
    public function save(Profile $profile): void;
}
