<?php

namespace App\Domain\User\Entities;

use App\Domain\Shared\AggregateRoot;
use App\Domain\User\Events\UserRegistered;
use App\Domain\User\ValueObjects\Email;

class User extends AggregateRoot
{
    private function __construct(
        private readonly string $id,
        private string $name,
        private Email $email,
    ) {}

    public static function register(string $id, string $name, Email $email): self
    {
        $user = new self($id, $name, $email);

        $user->recordDomainEvent(new UserRegistered(
            userId: $id,
            email: $email->value(),
            name: $name
        ));

        return $user;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function changeName(string $name): void
    {
        $this->name = $name;
    }

    public function changeEmail(Email $email): void
    {
        $this->email = $email;
    }
}
