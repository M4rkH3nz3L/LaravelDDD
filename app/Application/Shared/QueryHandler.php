<?php

namespace App\Application\Shared;

interface QueryHandler
{
    public function __invoke(Query $query): mixed;
}
