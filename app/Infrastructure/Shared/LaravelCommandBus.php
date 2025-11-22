<?php

namespace App\Infrastructure\Shared;

use App\Application\Shared\Command;
use App\Application\Shared\CommandBus;
use App\Application\Shared\CommandHandler;
use Illuminate\Contracts\Container\Container;

class LaravelCommandBus implements CommandBus
{
    public function __construct(
        private readonly Container $container,
        private readonly array $commandHandlerMap = [],
    ) {}

    public function dispatch(Command $command): mixed
    {
        $handler = $this->resolveHandler($command);

        return $handler($command);
    }

    private function resolveHandler(Command $command): CommandHandler
    {
        $commandClass = get_class($command);

        if (! isset($this->commandHandlerMap[$commandClass])) {
            throw new \RuntimeException("No handler registered for command: {$commandClass}");
        }

        $handlerClass = $this->commandHandlerMap[$commandClass];

        return $this->container->make($handlerClass);
    }
}
