<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

/*
 * Events listeners
 */

return [
    \App\Events\UserRegistered\UserRegisteredEvent::class => [
        \App\Events\UserRegistered\SendWelcomeNotification::class,
        \App\Events\UserRegistered\SendAdminNotification::class,
    ],

    \App\Events\UserCreated\UserCreatedEvent::class => [
        \App\Events\UserCreated\SendUserPasswordNotification::class,
    ],
];
