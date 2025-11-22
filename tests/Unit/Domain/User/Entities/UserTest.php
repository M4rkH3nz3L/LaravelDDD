<?php

use App\Domain\User\Entities\User;
use App\Domain\User\ValueObjects\Email;

it('creates a user with valid data', function () {
    $user = new User(
        id: '123',
        name: 'John Doe',
        email: new Email('john@example.com')
    );

    expect($user->id())->toBe('123');
    expect($user->name())->toBe('John Doe');
    expect($user->email()->value())->toBe('john@example.com');
});

it('can change user name', function () {
    $user = new User(
        id: '123',
        name: 'John Doe',
        email: new Email('john@example.com')
    );

    $user->changeName('Jane Doe');

    expect($user->name())->toBe('Jane Doe');
});

it('can change user email', function () {
    $user = new User(
        id: '123',
        name: 'John Doe',
        email: new Email('john@example.com')
    );

    $user->changeEmail(new Email('jane@example.com'));

    expect($user->email()->value())->toBe('jane@example.com');
});
