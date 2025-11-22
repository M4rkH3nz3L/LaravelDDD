<?php

namespace App\Application\User\Handlers;

use App\Application\Shared\Command;
use App\Application\Shared\CommandHandler;
use App\Application\User\Commands\RegisterUserCommand;
use App\Domain\Shared\ValueObjects\Uuid;
use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Email;

class RegisterUserHandler implements CommandHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function __invoke(Command $command): User
    {
        assert($command instanceof RegisterUserCommand);

        $data = $command->data;

        $user = User::register(
            id: Uuid::generate()->value(),
            name: $data->name,
            email: new Email($data->email),
        );

        $this->userRepository->save($user);

        return $user;
    }
}
