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
require_once 'config/errors.php';
require_once 'config/env.php';
require_once 'config/database.php';
require_once 'config/routes.php';
require_once 'config/middlewares.php';
require_once 'config/email.php';
require_once 'config/security.php';
require_once 'config/storage.php';

//start routing
new \Framework\Routing\Router();
