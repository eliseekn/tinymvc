<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Mailer configuration
 */

$config = [
    'default' => 'smtp',

    'sender_name' => 'TinyMVC',
    'sender_email' => 'tiny@mvc.framework',

    'smtp' => [
        'host' => 'localhost',
        'port' => 1025,
        'auth' => false,
        'secure' => false,
        'tls' => false,
        'username' => '',
        'password' => ''
    ],

    'sendmail' => []
];
