<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Framework\Support\Storage;

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
    ini_set('error_reporting', E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('error_reporting', 0);
}

//errors logger
if (config('errors.log') === true) {
    ini_set('log_errors', 1);
    ini_set('error_log', Storage::path(config('storage.logs'))->addFile('tinymvc_logs_' . date('m_d_y') . '.txt'));
} else {
    ini_set('log_errors', 0);
}
