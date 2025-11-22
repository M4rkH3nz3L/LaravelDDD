<?php

namespace App\Application\User\Handlers;

use App\Application\User\Commands\RegisterUserCommand;
use App\Domain\Shared\ValueObjects\Uuid;
use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Email;

class RegisterUserHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function handle(RegisterUserCommand $command): User
    {
        $data = $command->data;

        $user = new User(
            id: Uuid::generate()->value(),
            name: $data->name,
            email: new Email($data->email),
        );

        // In a real implementation, you would save via repository
        // $this->userRepository->save($user);

        return $user;
    }
}
