<?php

namespace App\Application\User\Queries;

use App\Application\Shared\Query;

class GetUserByIdQuery implements Query
{
    public function __construct(
        public readonly string $userId,
    ) {}
}
