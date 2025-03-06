<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

/*
 * Security configuration
 */

return [
    'encryption' => [
        'key' => env('ENCRYPTION_KEY'),
        'cookies' => true,
    ],

    'auth' => [
        'max_attempts' => false,
        'unlock_timeout' => 1, //in minutes
        'email_verification' => false,
        'identifier' => 'email',
    ],

    'session' => [
        'lifetime' => 3600 * 5, //in seconds
    ],

    'csrf_excluded_uri' => [
        '/logout',
        '/api/logout',
    ],

    'redirect_if_logged' => '/dashboard'
];
