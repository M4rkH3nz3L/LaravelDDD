<?php

namespace App\Application\Shared;

interface QueryBus
{
    public function dispatch(Query $query): mixed;
}
