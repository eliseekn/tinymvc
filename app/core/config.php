<?php
/**
* Application => TinyMVC (PHP framework based on MVC architecture)
* File        => config.php (application configuration file)
* Github      => https://github.com/eliseekn/tinymvc
* Copyright   => 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
* Licence     => MIT (https://opensource.org/licenses/MIT)
*/

//reset execution timeout
set_time_limit(0);

//define root directory
define('WEB_ROOT', 'http://localhost/tinymvc/'); //domain
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] .'/'); //document directory full path
define('LOGS_ROOT', DOCUMENT_ROOT .'logs/'); //logs directory

//define environment configuration
define('APP_ENV', 'development'); 

//define database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'eliseekn');
define('DB_NAME', 'tinymvc');
