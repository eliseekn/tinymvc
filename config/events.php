<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

use App\Events\UserCreated\SendUserPasswordNotification;
use App\Events\UserCreated\UserCreatedEvent;
use App\Events\UserRegistered\SendAdminNotification;
use App\Events\UserRegistered\SendWelcomeNotification;
use App\Events\UserRegistered\UserRegisteredEvent;
use Core\Event\Events\ModelNotFound\ModelNotFoundEvent;
use Core\Event\Events\ModelNotFound\SendNotFoundResponse;

/*
 * Events listeners
 */

return [
    UserRegisteredEvent::class => [
        SendWelcomeNotification::class,
        SendAdminNotification::class,
    ],

    UserCreatedEvent::class => [
        SendUserPasswordNotification::class,
    ],

    ModelNotFoundEvent::class => [
        SendNotFoundResponse::class,
    ],
];
