<?php

declare(strict_types=1);

namespace App\Dashboard\AiChatbot\Application\SendMessage;

use App\Dashboard\AiChatbot\Domain\Entity\Message;
use App\Dashboard\AiChatbot\Domain\Service\ChatServiceInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class SendMessageCommandHandler
{
    public function __construct(
        private readonly ChatServiceInterface $chatService
    ) {}

    public function __invoke(SendMessageCommand $command): StreamedResponse
    {
        return $this->chatService->sendMessage($command->message());
    }
}