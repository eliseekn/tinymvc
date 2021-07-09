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
    'dsn' => 'mysql:host=' . env('DB_HOST', 'localhost') . ';dbname=' . env('DB_NAME', 'tinymvc'),
    'host' => env('DB_HOST', 'localhost'),
    'name' => env('DB_NAME', 'tinymvc'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', 'root'),
    'table_prefix' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'timestamps' => true,
    'engine' => 'InnoDB'
];