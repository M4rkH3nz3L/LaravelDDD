<?php

namespace App\Application\User\Commands;

use App\Application\Shared\Command;
use App\Application\User\DTO\RegisterUserDTO;

class RegisterUserCommand implements Command
{
    public function __construct(
        public readonly RegisterUserDTO $data,
    ) {}
}
