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
 * Main application file
 */

//include composer autoloader
require 'vendor/autoload.php';

//load configurations files
require_once 'config/app.php';
require_once 'config/env.php';
require_once 'config/database.php';
require_once 'config/routes.php';
require_once 'config/middlewares.php';

new \Framework\Core\Router();