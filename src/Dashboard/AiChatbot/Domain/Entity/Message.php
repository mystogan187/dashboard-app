<?php

declare(strict_types=1);

namespace App\Dashboard\AiChatbot\Domain\Entity;

use DateTimeImmutable;
use DateTimeInterface;

final class Message
{
    private string $id;
    private string $content;
    private bool $isUser;
    private DateTimeImmutable $timestamp;

    public function __construct(
        string $content,
        bool $isUser,
        ?string $id = null
    ) {
        $this->id = $id ?? uniqid();
        $this->content = $content;
        $this->isUser = $isUser;
        $this->timestamp = new DateTimeImmutable();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'isUser' => $this->isUser,
            'timestamp' => $this->timestamp->format(DateTimeInterface::RFC3339),
        ];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isUser(): bool
    {
        return $this->isUser;
    }

    public function getTimestamp(): DateTimeImmutable
    {
        return $this->timestamp;
    }
}