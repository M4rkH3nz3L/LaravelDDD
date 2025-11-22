<?php

namespace App\Infrastructure\Providers;

use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\User\UserRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class DddServiceProvider extends ServiceProvider
{
    public function register(): void
    {
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
