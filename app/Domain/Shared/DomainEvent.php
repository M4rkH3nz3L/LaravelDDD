<?php

namespace App\Domain\Shared;

use DateTimeImmutable;

abstract class DomainEvent
{
    private DateTimeImmutable $occurredOn;

    public function __construct()
    {
        $this->occurredOn = new DateTimeImmutable;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    abstract public function aggregateId(): string;

    abstract public function eventName(): string;

    public function toArray(): array
    {
        return [
            'event_name' => $this->eventName(),
            'aggregate_id' => $this->aggregateId(),
            'occurred_on' => $this->occurredOn->format('Y-m-d H:i:s'),
        ];
    }
}
