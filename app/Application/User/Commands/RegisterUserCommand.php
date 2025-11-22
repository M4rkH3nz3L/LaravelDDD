<?php

namespace App\Application\User\Commands;

use App\Application\User\DTO\RegisterUserDTO;

class RegisterUserCommand
{
    public function __construct(
        public readonly RegisterUserDTO $data,
    ) {}
}
