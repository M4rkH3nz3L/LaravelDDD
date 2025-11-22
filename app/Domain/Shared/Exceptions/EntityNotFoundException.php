<?php

namespace App\Domain\Shared\Exceptions;

class EntityNotFoundException extends DomainException
{
    public function __construct(string $entityName, string $id)
    {
        parent::__construct("Entity [{$entityName}] with ID [{$id}] not found");
    }
}
