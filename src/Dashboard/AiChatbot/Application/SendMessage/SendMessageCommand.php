<?php

declare(strict_types=1);

namespace App\Dashboard\AiChatbot\Application\SendMessage;

use App\Dashboard\Shared\Domain\Bus\Command\Command;

final class SendMessageCommand implements Command
{
    public function __construct(
        private readonly string $message
    ) {}

    public function message(): string
    {
        return $this->message;
    }
}
