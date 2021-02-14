<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Routing\Route;
use Framework\Routing\View;

/**
 * Web routes
 */

Route::get('/', ['handler' => 'HomeController@index']);
