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

//include core and configuration files
require_once 'app/config/app.php';
require_once 'app/config/database.php';
require_once 'app/config/routes.php';
require_once 'app/core/database.php';
require_once 'app/core/model.php';
require_once 'app/core/loader.php';
require_once 'app/core/router.php';
require_once 'app/core/http.php';

//set errors display
if (DISPLAY_ERRORS == true) {
    ini_set('display_errors', 1);
    ini_set('error_reporting', -1);
} else {
    ini_set('display_errors', 0);
    ini_set('error_reporting', 0);
}

//include necessaries helpers
load_helpers('');

//start routing system
$router = new Router();
$router->add_custom_routes($routes);
$router->dispatch();
