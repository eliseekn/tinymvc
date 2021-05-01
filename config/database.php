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
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'host' => env('DB_HOST', 'localhost'),
    'name' => env('DB_NAME', 'test'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', 'root'),
    'table_prefix' => '',
    'timestamps' => true,
    'storage_engine' => 'InnoDB'
];