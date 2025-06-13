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
 * Testing configuration
 */

return [
    'url' => [
        'protocol' => env('TESTING_URL_PROTOCOL', 'http://'),
        'host' => env('TESTING_URL_HOST', '127.0.0.1'),
        'port' => env('TESTING_UL_PORT', 8889),
    ],

    'browser' => [
        'name' => \Symfony\Component\Panther\PantherTestCase::FIREFOX,
        'headless' => true,
        'screenshots_dir' => config('storage.tmp'),
    ],

    'database' => [
        'driver' => env('TESTING_DB_DRIVER', DatabaseDriver::SQLITE),
        'name' => env('TESTING_DB_NAME', 'tinymvc_test'),
        'host' => env('TESTING_DB_HOST', ''),
        'port' => env('TESTING_DB_PORT', ''),
        'username' => env('TESTING_DB_USERNAME', ''),
        'password' => env('TESTING_DB_PASSWORD', ''),
        'memory' => false,
    ],
];
