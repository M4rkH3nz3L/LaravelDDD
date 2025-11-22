<?php

namespace App\Domain\User\Entities;

use App\Domain\User\ValueObjects\Email;

class User
{
    public function __construct(
        private readonly string $id,
        private string $name,
        private Email $email,
    ) {}

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
