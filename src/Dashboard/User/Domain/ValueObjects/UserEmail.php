<?php

namespace App\Dashboard\User\Domain\ValueObjects;

final class UserEmail
{
    private function __construct(private readonly string $value)
    {
        $this->ensureIsValidEmail($value);
    }

    public static function from(string $email): self
    {
        return new self($email);
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