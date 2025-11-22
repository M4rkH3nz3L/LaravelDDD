<?php

namespace App\Application\Shared;

interface CommandBus
{
    public function dispatch(Command $command): mixed;
}
