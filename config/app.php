<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

/**
 * Application configuration
 */

$config = [
    'app' => [
        'name' => 'tinymvc',
        'folder' => '/tinymvc',
        'url' => 'http://localhost/tinymvc'
    ],

    'database' => [
        'charset' => 'utf8',
        'host' => 'localhost',
        'name' => 'test',
        'username' => 'root',
        'password' => 'root',
        'table_prefix' => ''
    ],

    'mailer' => [
        'host' => 'localhost',
        'port' => 25,
        'username' => '',
        'password' => '',
        'from' => 'admin@mail.com',
        'name' => 'Admin'
    ],

    'errors' => [
        'display' => true,

        'views' => [
            '404' => 'errors' . DIRECTORY_SEPARATOR . '404',
            '403' => 'errors' . DIRECTORY_SEPARATOR . '404'
        ]
    ],

    'security' => [
        'enc_key' => base64_encode('write_something_here_to_generate_a_single_encryption_key'),

        'auth' => [
            'max_attempts' => 5,
            'unlock_timeout' => 1
        ]
    ],

    'storage' => [
        'public' => APP_ROOT . 'public' . DIRECTORY_SEPARATOR,
        'views' => APP_ROOT . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR,
        'migrations' => APP_ROOT . 'app' . DIRECTORY_SEPARATOR . 'Database' . DIRECTORY_SEPARATOR . 'Migrations' . DIRECTORY_SEPARATOR,
        'seeds' => APP_ROOT . 'app' . DIRECTORY_SEPARATOR . 'Database' . DIRECTORY_SEPARATOR . 'Seeds' . DIRECTORY_SEPARATOR
    ],

    'session' => [
        'lifetime' => 3600 //in seconds
    ]
];
