<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Mailer configuration
 */

return [
    'transport' => env('MAILER_TRANSPORT', 'smtp'),

    'sender' => [
        'name' => config('app.name'),
        'email' => 'tiny@mvc.framework',
    ],

    'smtp' => [
        'host' => env('MAILER_HOST', '127.0.0.1'),
        'port' => env('MAILER_PORT', 1025),
        'auth' => false,
        'secure' => false,
        'tls' => false,
        'username' => env('MAILER_USERNAME', ''),
        'password' => env('MAILER_PASSWORD', '')
    ]
];
