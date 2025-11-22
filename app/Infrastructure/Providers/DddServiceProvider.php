<?php

namespace App\Infrastructure\Providers;

use App\Application\Shared\CommandBus;
use App\Application\Shared\QueryBus;
use App\Domain\Shared\Contracts\DomainEventDispatcher;
use App\Domain\Shared\Contracts\TransactionManager;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\User\UserRepositoryEloquent;
use App\Infrastructure\Shared\LaravelCommandBus;
use App\Infrastructure\Shared\LaravelDomainEventDispatcher;
use App\Infrastructure\Shared\LaravelQueryBus;
use App\Infrastructure\Shared\LaravelTransactionManager;
use Illuminate\Support\ServiceProvider;

class DddServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register infrastructure bindings
        $this->app->bind(
            TransactionManager::class,
            LaravelTransactionManager::class
        );

        $this->app->singleton(
            DomainEventDispatcher::class,
            LaravelDomainEventDispatcher::class
        );

        // Register Command and Query buses
        $this->app->singleton(CommandBus::class, function ($app) {
            return new LaravelCommandBus(
                $app,
                config('ddd.commands', [])
            );
        });

        $this->app->singleton(QueryBus::class, function ($app) {
            return new LaravelQueryBus(
                $app,
                config('ddd.queries', [])
            );
        });

        // Register repository bindings
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepositoryEloquent::class
        );
    }

    public function boot(): void
    {
        //
    }
}
