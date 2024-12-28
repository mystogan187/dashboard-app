<?php

// Domain/Profile/ValueObject/Password.php
namespace App\Dashboard\Profile\Domain\ValueObjects;

final class Password
{
    private function __construct(private readonly string $value)
    {
        $this->ensureIsValidPassword($value);
    }

    public static function from(string $value): self
    {
        return new self($value);
    }

    private function ensureIsValidPassword(string $password): void
    {
        if (strlen($password) < 6) {
            throw new \InvalidArgumentException('Password must be at least 6 characters');
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}