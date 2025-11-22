<?php

namespace App\Application\Shared;

interface CommandHandler
{
    public function __invoke(Command $command): mixed;
}
