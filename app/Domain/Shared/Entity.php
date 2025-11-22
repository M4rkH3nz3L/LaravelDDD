<?php

namespace App\Domain\Shared;

abstract class Entity
{
    private array $domainEvents = [];

    abstract public function id(): string;

    public function equals(self $other): bool
    {
        return $this->id() === $other->id() && get_class($this) === get_class($other);
    }

    protected function recordDomainEvent(DomainEvent $event): void
    {
        $this->domainEvents[] = $event;
    }

    public function pullDomainEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];

        return $events;
    }

    public function hasDomainEvents(): bool
    {
        return count($this->domainEvents) > 0;
    }
}
