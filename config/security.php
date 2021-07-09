<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Security configuration
 */

return [
    'encryption' => [
        'key' => env('ENCRYPTION_KEY'),
        'cookies' => true,
    ],

    'auth' => [
        'max_attempts' => false,
        'unlock_timeout' => 1, //in minute
        'email_verification' => false,
    ],

    'session' => [
        'lifetime' => 3600 * 5, //in seconds
    ]
];
