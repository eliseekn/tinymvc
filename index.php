<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

use Framework\App;

/**
 * Main application entry
 */

//load packages and main configuration
require 'vendor/autoload.php';
require_once 'config/env.php';

//start application
$app = new App();
$app->start();
