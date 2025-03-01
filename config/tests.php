<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

/*
 * Testing configuration
 */

return [
    'database' => [
        'driver' => 'sqlite',
        'suffix' => '_test',
    ],

    'host' => '127.0.0.1',
    'port' => 8889,

    'browser' => [
        \Symfony\Component\Panther\PantherTestCase::FIREFOX, //browser name
        'headless' => true,
        'screenshots_dir' => config('storage.tmp'),
    ],
];
