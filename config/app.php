<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

/*
 * Application configuration
 */

return [
    'home' => '/',
    'env' => env('APP_ENV', 'local'), //local, test or prod
    'name' => env('APP_NAME', 'TinyMVC'),
    'url' => env('APP_URL', 'http://127.0.0.1:8888/'),
    'lang' => env('APP_LANG', 'en'),
];
