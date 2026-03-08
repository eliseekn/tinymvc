<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

/*
 * Security configuration
 */

return [
    'encryption' => [
        'key' => env('APP_ENCRYPTION_KEY'),
        'cookies' => true,
        'cache' => false,
    ],

    'auth' => [
        'max_attempts' => env('AUTH_MAX_ATTEMPTS', 0),
        'unlock_timeout' => env('AUTH_UNLOCK_TIMEOUT', 1), // in minutes
        'email_verification' => env('AUTH_EMAIL_VERIFICATION', false, FILTER_VALIDATE_BOOLEAN),
        'identifier' => env('AUTH_IDENTIFIER', 'email'),
        'redirect_after_login' => '/dashboard',
    ],

    'session' => [
        'lifetime' => 3600 * 5, // in seconds
    ],

    'csrf_excluded_uri' => [
        '/logout',
    ],
];
