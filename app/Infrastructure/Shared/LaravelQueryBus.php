<?php

namespace App\Infrastructure\Shared;

use App\Application\Shared\Query;
use App\Application\Shared\QueryBus;
use App\Application\Shared\QueryHandler;
use Illuminate\Contracts\Container\Container;

class LaravelQueryBus implements QueryBus
{
    public function __construct(
        private readonly Container $container,
        private readonly array $queryHandlerMap = [],
    ) {}

    public function dispatch(Query $query): mixed
    {
        $handler = $this->resolveHandler($query);

        return $handler($query);
    }

    private function resolveHandler(Query $query): QueryHandler
    {
        $queryClass = get_class($query);

        if (! isset($this->queryHandlerMap[$queryClass])) {
            throw new \RuntimeException("No handler registered for query: {$queryClass}");
        }

        $handlerClass = $this->queryHandlerMap[$queryClass];

        return $this->container->make($handlerClass);
    }
}
