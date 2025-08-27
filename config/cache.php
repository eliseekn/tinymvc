<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

use Core\Enums\CacheDriver;

/*
 * Cache configuration
 */

return [
    'connection' => env('CACHE_CONNECTION', 'file'),

    'file' => [
        'driver' => CacheDriver::FILE,
    ],

    'redis' => [
        'driver' => CacheDriver::REDIS,
        'scheme' => env('REDIS_SCHEME', 'tcp'),
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'port' => env('REDIS_PORT', 6379),
    ],
];
