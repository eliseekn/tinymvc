<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

use Core\Enums\DatabaseDriver;

/*
 * Database configuration
 */

return [
    'connection' => env('DB_CONNECTION', 'mysql'),
    'table_prefix' => '',

    'mysql' => [
        'driver' => DatabaseDriver::MYSQL,
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'name' => env('DB_NAME', 'tinymvc'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'engine' => 'InnoDB',
    ],

    'pgsql' => [
        'driver' => DatabaseDriver::PGSQL,
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '5432'),
        'name' => env('DB_NAME', 'tinymvc'),
        'username' => env('DB_USERNAME', 'postgres'),
        'password' => env('DB_PASSWORD', 'password'),
        'encoding' => 'utf8',
    ],

    'sqlite' => [
        'driver' => DatabaseDriver::SQLITE,
        'name' => env('DB_NAME', 'tinymvc'),
        'memory' => false,
    ],

    'testing' => [
        'driver' => env('TESTING_DB_DRIVER', DatabaseDriver::SQLITE),
        'name' => env('TESTING_DB_NAME', 'tinymvc_test'),
        'host' => env('TESTING_DB_HOST', ''),
        'port' => env('TESTING_DB_PORT', ''),
        'username' => env('TESTING_DB_USERNAME', ''),
        'password' => env('TESTING_DB_PASSWORD', ''),
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'engine' => 'InnoDB',
        'memory' => false,
    ],
];
