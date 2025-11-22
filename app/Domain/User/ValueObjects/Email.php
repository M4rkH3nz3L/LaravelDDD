<?php

namespace App\Domain\User\ValueObjects;

use InvalidArgumentException;

class Email
{
    public function __construct(
        private readonly string $value,
    ) {
        $this->validate($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    private function validate(string $value): void
    {
        if (empty($value)) {
            throw new InvalidArgumentException('Email cannot be empty');
        }

        if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email format: {$value}");
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
