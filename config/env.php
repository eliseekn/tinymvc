<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

/**
 * Set application environnement
 */

//application root path
define('APP_ROOT', __DIR__  . DIRECTORY_SEPARATOR . '../');

//remove PHP maximum execution time 
set_time_limit(0);

//exceptions handler
if (config('errors.display') === true) {
    ini_set('display_errors', 1);
    ini_set('error_reporting', -1);
} else {
    ini_set('display_errors', 0);
    ini_set('error_reporting', 0);
}
