<?php

declare(strict_types=1);

namespace App\Dashboard\AiChatbot\Domain\Service;

use Symfony\Component\HttpFoundation\StreamedResponse;

interface ChatServiceInterface
{
    public function sendMessage(string $message): StreamedResponse;
}