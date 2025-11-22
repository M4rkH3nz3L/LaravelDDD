<?php

namespace App\Application\User\Handlers;

use App\Application\Shared\Query;
use App\Application\Shared\QueryHandler;
use App\Application\User\Queries\GetUserByIdQuery;
use App\Domain\Shared\Exceptions\EntityNotFoundException;
use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepositoryInterface;

class GetUserByIdHandler implements QueryHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function __invoke(Query $query): User
    {
        assert($query instanceof GetUserByIdQuery);

        $user = $this->userRepository->find($query->userId);

        if (! $user) {
            throw new EntityNotFoundException('User', $query->userId);
        }

        return $user;
    }
}
