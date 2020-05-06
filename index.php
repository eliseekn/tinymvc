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
 * Main application file
 */

//include core files
require_once 'app/config/app.php';
require_once 'app/config/database.php';
require_once 'app/config/routes.php';
require_once 'app/core/database.php';
require_once 'app/core/model.php';
require_once 'app/core/loader.php';
require_once 'app/core/router.php';

//set error_reporting() and display_errors parameters
//change application environment settings in app/core/config.php
if (APP_ENV == 'development') {
    ini_set('display_errors', 1);
    ini_set('error_reporting', -1);
} else if (APP_ENV == 'production') {
    ini_set('display_errors', 0);
    ini_set('error_reporting', 0);
} else {
    echo 'The application environment is not set properly.';
    exit();
}

//include necessaries helpers
load_helpers(
    'url',
    'debug'
);

//start url routing
$router = new Router();
$router->add_custom_routes($routes); //add custom routes
$router->dispatch(); //dispatch parameters
