<?php

namespace App\Domain\User\ValueObjects;

use App\Domain\Shared\ValueObject;
use InvalidArgumentException;

class Email extends ValueObject
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

    public function equals(ValueObject $other): bool
    {
        return $other instanceof self && $this->value === $other->value;
    }

    public function toNative(): string
    {
        return $this->value;
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
}
