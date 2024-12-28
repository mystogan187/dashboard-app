<?php

namespace App\Dashboard\User\Domain\ValueObjects;

final class UserId
{
    private function __construct(private readonly int $value)
    {
        $this->ensureIsValidId($value);
    }

    public static function from(int $id): self
    {
        return new self($id);
    }

    private function ensureIsValidId(int $id): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('User id must be greater than 0');
        }
    }

    public function value(): int
    {
        return $this->value;
    }
}