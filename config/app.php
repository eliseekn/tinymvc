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

//define application root folder. Leave empty for www root
define('ROOT_FOLDER', '/tinymvc');

//domain url
define('WEB_DOMAIN', 'http://localhost' . ROOT_FOLDER);

//absolute application path
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);

//public folder path
if (empty(ROOT_FOLDER)) {
    define('PUBLIC_STORAGE', DOCUMENT_ROOT . 'public' . DIRECTORY_SEPARATOR);
} else {
    define('PUBLIC_STORAGE', DOCUMENT_ROOT . trim(ROOT_FOLDER, '/') . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
}

//errors display configuration
define('DISPLAY_ERRORS', true);

//session lifetime in seconds
define('SESSION_LIFETIME', 3600);

//errors page
define('ERRORS_PAGE', [
    '404' => 'errors/404',
    '403' => 'errors/403'
]);

//encryption key
define('ENC_KEY', 'BIu5sSkxjVzqiMlHFcX42WpEK3ahUyLG9DQNogZJnmYwArT10R');
