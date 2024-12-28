<?php

namespace App\Dashboard\Profile\Application\UploadPhoto;

use App\Dashboard\Profile\Application\ProfileResponse;
use App\Dashboard\User\Domain\Infrastructure\UserRepository;
use App\Dashboard\Profile\Domain\Services\PhotoUploader;
use App\Dashboard\User\Domain\ValueObjects\UserId;

final class UploadPhotoHandler
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly PhotoUploader $photoUploader
    ) {}

    public function __invoke(UploadPhotoCommand $command): ProfileResponse
    {
        $user = $this->repository->findById(UserId::from($command->userId()));
        if ($user === null) {
            throw new \DomainException('User not found');
        }

        $fileName = $this->photoUploader->upload($command->photo(), $user->id()->value());

        $user->updateProfilePhoto($fileName);
        $this->repository->save($user);

        return ProfileResponse::fromUser($user);
    }
}