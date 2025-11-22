<?php

namespace App\Infrastructure\Shared;

use App\Domain\Shared\Contracts\DomainEventDispatcher;
use App\Domain\Shared\DomainEvent;
use Illuminate\Contracts\Events\Dispatcher;

class LaravelDomainEventDispatcher implements DomainEventDispatcher
{
    public function __construct(
        private readonly Dispatcher $dispatcher,
    ) {}

    public function dispatch(DomainEvent $event): void
    {
        $this->dispatcher->dispatch($event);
    }

    public function dispatchAll(array $events): void
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }
}
