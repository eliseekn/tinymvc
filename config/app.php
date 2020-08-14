<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

/**
 * Application main configuration
 */

//define application name
define('APP_NAME', 'tinymvc');

//define application folder name, leave empty for www folder
define('APP_FOLDER', '/tinymvc');

//define absolute application path
define('APP_ROOT', __DIR__ . DIRECTORY_SEPARATOR . '../');

//define application domain url
define('APP_DOMAIN', 'http://localhost' . APP_FOLDER);

//define session lifetime, in seconds
define('SESSION_LIFETIME', 3600);
