<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Security configuration
 */

$config = [
    'app' => [
        'name' => env('APP_NAME', 'TinyMVC'),
        'folder' => env('APP_FOLDER', 'tinymvc'),
        'url' => env('APP_URL', 'http://localhost/tinymvc'),
        'lang' => env('APP_LANG', 'en')
    ]
];
