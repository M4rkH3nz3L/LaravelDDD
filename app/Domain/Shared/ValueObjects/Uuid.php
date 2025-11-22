<?php

namespace App\Domain\Shared\ValueObjects;

use App\Domain\Shared\ValueObject;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid extends ValueObject
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
        if (! RamseyUuid::isValid($value)) {
            throw new InvalidArgumentException("Invalid UUID format: {$value}");
        }
    }
}
