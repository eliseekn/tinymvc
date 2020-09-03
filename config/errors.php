<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

/**
 * Application main configuration
 */

//errors display configuration
define('DISPLAY_ERRORS', true);

//errors page
define('ERRORS_PAGE', [
    '404' => 'errors' . DIRECTORY_SEPARATOR . '404',
    '403' => 'errors' . DIRECTORY_SEPARATOR . '403'
]);
