<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

/*
 * Notifications configuration
 */

return [
    'mail' => [
        'sender' => [
            'name' => env('MAILER_SENDER_NAME', 'TinyMVC'),
            'email' => env('MAILER_SENDER_MAIL', 'no-reply@tiny.mvc'),
        ],

        'smtp' => [
            'host' => env('MAILER_HOST', '127.0.0.1'),
            'port' => env('MAILER_PORT', 1025),
            'auth' => false,
            'secure' => false,
            'tls' => false,
            'username' => env('MAILER_USERNAME', ''),
            'password' => env('MAILER_PASSWORD', ''),
        ],
    ],
];
