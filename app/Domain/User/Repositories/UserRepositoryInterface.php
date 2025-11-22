<?php

namespace App\Domain\User\Repositories;

use App\Domain\Shared\Contracts\RepositoryInterface;
use App\Domain\User\Entities\User;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function findByEmail(string $email): ?User;
}
