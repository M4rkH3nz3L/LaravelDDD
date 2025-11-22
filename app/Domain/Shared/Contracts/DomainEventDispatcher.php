<?php

namespace App\Domain\Shared\Contracts;

use App\Domain\Shared\DomainEvent;

interface DomainEventDispatcher
{
    public function dispatch(DomainEvent $event): void;

    public function dispatchAll(array $events): void;
}
