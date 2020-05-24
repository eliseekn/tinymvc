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
require 'config/app.php';
require 'config/env.php';
require 'config/database.php';
require 'config/routes.php';
require 'config/middlewares.php';

new \Framework\Core\Router();