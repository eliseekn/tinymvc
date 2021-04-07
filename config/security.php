<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Application configuration
 */

$config = [
    'encryption' => [
        'key' => base64_encode('write_something_here_to_set_your_single_encryption_key'),
        'cookies' => true,
    ],

    'auth' => [
        'max_attempts' => 0, //set to 0 to disable
        'unlock_timeout' => 1, //in minute
        'email_confirmation' => false
    ],

    'session' => [
        'lifetime' => 3600 * 5, //5 hours in seconds
    ]
];
