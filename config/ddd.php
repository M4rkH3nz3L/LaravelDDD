<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Command Handler Mapping
    |--------------------------------------------------------------------------
    |
    | Map commands to their handlers for the CommandBus.
    |
    */
    'commands' => [
        \App\Application\User\Commands\RegisterUserCommand::class => \App\Application\User\Handlers\RegisterUserHandler::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Query Handler Mapping
    |--------------------------------------------------------------------------
    |
    | Map queries to their handlers for the QueryBus.
    |
    */
    'queries' => [
        \App\Application\User\Queries\GetUserByIdQuery::class => \App\Application\User\Handlers\GetUserByIdHandler::class,
    ],
];
