<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Framework\Application;

/**
 * Main application entry
 */

//load packages and main configuration
require 'vendor/autoload.php';
require_once 'config/env.php';
require_once 'config/errors.php';

//start application
$app = new Application();
$app->run();
