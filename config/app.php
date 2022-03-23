<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Application configuration
 */

return [
    'home' => '/',
    'env' => env('APP_ENV', 'local'), //test, prod
    'name' => env('APP_NAME', 'TinyMVC'),
    'url' => env('APP_URL', 'http://127.0.0.1:8080/'),
    'lang' => env('APP_LANG', 'en'),
];
