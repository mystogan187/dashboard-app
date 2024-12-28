<?php

namespace App\Dashboard\Profile\Application\UploadPhoto;

use Symfony\Component\HttpFoundation\File\UploadedFile;

final class UploadPhotoCommand
{
    public function __construct(
        private readonly int $userId,
        private readonly UploadedFile $photo
    ) {}

    public function userId(): int
    {
        return $this->userId;
    }

    public function photo(): UploadedFile
    {
        return $this->photo;
    }
}
