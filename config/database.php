<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
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

    'custom' => [
        'driver' => DatabaseDriver::PGSQL,
        'host' => '127.0.0.1',
        'port' => '5432',
        'name' => 'pharma_delivery',
        'username' => 'postgres',
        'password' => 'password',
        'encoding' => 'utf8',
    ],
];
