<?php

use Framework\Http\Route;
use Framework\Http\Middleware;

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

//dump_exit(Route::$routes, Route::$names, Middleware::$names, Middleware::$middlewares);

//start routing system
$router = new \Framework\Core\Router();
$router->dispatch();