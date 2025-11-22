<?php

namespace App\Domain\Shared\ValueObjects;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid
{
    public function __construct(
        private readonly string $value,
    ) {
        $this->validate($value);
    }

    public static function generate(): self
    {
        return new self(RamseyUuid::uuid4()->toString());
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
        if (! RamseyUuid::isValid($value)) {
            throw new InvalidArgumentException("Invalid UUID format: {$value}");
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
