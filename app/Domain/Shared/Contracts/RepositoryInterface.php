<?php

namespace App\Domain\Shared\Contracts;

interface RepositoryInterface
{
    public function find(string $id): mixed;

    public function all(): array;

    public function save(mixed $entity): mixed;

    public function delete(string $id): bool;
}
