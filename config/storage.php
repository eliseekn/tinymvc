<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

/**
 * Storages configuration
 */

//define storage paths
define('STORAGE', [
    'public' => APP_ROOT . 'public' . DIRECTORY_SEPARATOR,
    'templates' => APP_ROOT . 'templates' . DIRECTORY_SEPARATOR,
    'migrations' => APP_ROOT . 'app' . DIRECTORY_SEPARATOR . 'Database' . DIRECTORY_SEPARATOR . 'Migrations' . DIRECTORY_SEPARATOR,
    'seeds' => APP_ROOT . 'app' . DIRECTORY_SEPARATOR . 'Database' . DIRECTORY_SEPARATOR . 'Seeds' . DIRECTORY_SEPARATOR
]);
