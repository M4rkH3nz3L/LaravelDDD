<?php

namespace App\Infrastructure\Shared;

use App\Domain\Shared\Contracts\TransactionManager;
use Illuminate\Support\Facades\DB;

class LaravelTransactionManager implements TransactionManager
{
    public function transactional(callable $callback): mixed
    {
        return DB::transaction($callback);
    }
}
