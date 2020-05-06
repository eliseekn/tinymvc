<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Application configuration
 */

/**
 * reset execution timeout
 */
set_time_limit(0);

/**
 * web domain
 */
define('WEB_DOMAIN', 'http://localhost/tinymvc/');

/**
 * absolute application path
 */
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/'); //document directory full path

/**
 * errors display configuration
 */
define('DISPLAY_ERRORS', true);

/**
 * default router controller
 */
define('DEFAULT_CONTROLLER', 'home');

/**
 * default router action
 */
define('DEFAULT_ACTION', 'index');
