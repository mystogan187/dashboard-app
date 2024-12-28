<?php

// Domain/Profile/ValueObject/Email.php
declare(strict_types=1);

namespace App\Dashboard\Profile\Domain\ValueObjects;

final class Email
{
    private function __construct(private readonly string $value)
    {
        $this->ensureIsValidEmail($value);
    }

    public static function from(string $value): self
    {
        return new self($value);
    }

    private function ensureIsValidEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}