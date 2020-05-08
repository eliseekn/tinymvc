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
 * Add custom routes for redirection
 */

/**
 * default router controller
 */
define('DEFAULT_CONTROLLER', 'home');

/**
 * default router action
 */
define('DEFAULT_ACTION', 'index');

/**
 * custom routes
 */
$routes = array();
$routes['home/page'] = 'home/index';
$routes['posts/slug'] = 'posts/index';
$routes['dashboard/posts'] = 'dashboard/index';