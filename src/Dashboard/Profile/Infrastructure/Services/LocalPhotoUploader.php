<?php

namespace App\Dashboard\Profile\Infrastructure\Services;

use App\Dashboard\Profile\Domain\Services\PhotoUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class LocalPhotoUploader implements PhotoUploader
{
    private const MAX_SIZE = 5 * 1024 * 1024; // 5MB
    private const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

    public function __construct(
        private readonly string $uploadDirectory
    ) {}

    public function upload(UploadedFile $file, int $userId): string
    {
        if (!in_array($file->getMimeType(), self::ALLOWED_TYPES)) {
            throw new \DomainException('Invalid image format. Use JPG, PNG or WebP');
        }

        if ($file->getSize() > self::MAX_SIZE) {
            throw new \DomainException('Image size must not exceed 5MB');
        }

        $fileName = sprintf(
            '%s-%s.%s',
            $userId,
            uniqid(),
            $file->guessExtension()
        );

        $file->move($this->uploadDirectory, $fileName);

        return $fileName;
    }
}
