<?php

namespace App\Dashboard\Profile\Domain\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface PhotoUploader
{
    public function upload(UploadedFile $file, int $userId): string;
}