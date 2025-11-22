<?php

use App\Domain\User\ValueObjects\Email;

it('creates a valid email', function () {
    $email = new Email('test@example.com');

    expect($email->value())->toBe('test@example.com');
});

it('throws exception for empty email', function () {
    new Email('');
})->throws(InvalidArgumentException::class, 'Email cannot be empty');

it('throws exception for invalid email format', function () {
    new Email('invalid-email');
})->throws(InvalidArgumentException::class, 'Invalid email format');

it('can compare two emails', function () {
    $email1 = new Email('test@example.com');
    $email2 = new Email('test@example.com');
    $email3 = new Email('other@example.com');

    expect($email1->equals($email2))->toBeTrue();
    expect($email1->equals($email3))->toBeFalse();
});

it('can be converted to string', function () {
    $email = new Email('test@example.com');

    expect((string) $email)->toBe('test@example.com');
});
