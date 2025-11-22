<?php

namespace App\Domain\Shared\Contracts;

interface TransactionManager
{
    public function transactional(callable $callback): mixed;
}
