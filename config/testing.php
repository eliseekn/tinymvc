<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

use Symfony\Component\Panther\PantherTestCase;

/*
 * Testing configuration
 */

return [
    'url' => [
        'protocol' => env('TESTING_URL_PROTOCOL', 'http://'),
        'host' => env('TESTING_URL_HOST', '127.0.0.1'),
        'port' => env('TESTING_URL_PORT', 8889),
    ],

    'browser' => [
        'name' => PantherTestCase::FIREFOX,
        'headless' => env('TESTING_BROWSER_HEADLESS', true, FILTER_VALIDATE_BOOLEAN),
        'screenshots_dir' => config('storage.tmp'),
    ],
];
