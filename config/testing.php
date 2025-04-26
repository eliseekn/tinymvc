<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

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
];
