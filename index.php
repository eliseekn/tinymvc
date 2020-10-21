<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

/**
 * Main pplication entry
 */

//load packages and configurations
require 'vendor/autoload.php';
require_once 'config/env.php';
require_once 'routes/app.php';
require_once 'routes/web.php';

//start routing
new \Framework\Routing\Router();
