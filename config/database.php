<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Database configuration
 */

return [
    'driver' => env('DB_DRIVER', 'mysql'),
    'table_prefix' => '',
    'name' => env('DB_NAME', 'tinymvc'),
    'timestamps' => true,

    'mysql' => [
        'host' => env('DB_HOST', 'localhost'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', 'root'),
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'engine' => 'InnoDB'
    ],

    'sqlite' => [
        'memory' => false,
    ]
];
