<?php

namespace App\Domain\User\Events;

use App\Domain\Shared\DomainEvent;

class UserRegistered extends DomainEvent
{
    public function __construct(
        private readonly string $userId,
        private readonly string $email,
        private readonly string $name,
    ) {
        parent::__construct();
    }

    public function aggregateId(): string
    {
        return $this->userId;
    }

    public function eventName(): string
    {
        return 'user.registered';
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'user_id' => $this->userId,
            'email' => $this->email,
            'name' => $this->name,
        ]);
    }
}
