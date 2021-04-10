<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Database configuration
 */

$config = [
    'mysql' => [
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'host' => env('MYSQL_HOST', 'localhost'),
        'database' => env('MYSQL_DATABASE', 'test'),
        'username' => env('MYSQL_USERNAME', 'root'),
        'password' => env('MYSQL_PASSWORD', 'root'),
        'table_prefix' => '',
        'timestamps' => true,
        'storage_engine' => 'InnoDB'
    ]
];