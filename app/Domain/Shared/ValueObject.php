<?php

namespace App\Domain\Shared;

abstract class ValueObject
{
    abstract public function equals(self $other): bool;

    abstract public function toNative(): mixed;

    public function __toString(): string
    {
        $native = $this->toNative();

        return is_scalar($native) ? (string) $native : json_encode($native);
    }
}
