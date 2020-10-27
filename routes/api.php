<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

use Framework\HTTP\Response;
use Framework\Routing\Route;

/**
 * Set api routes
 */

Route::get('/api', [
    'handler' => function() {
        Response::sendJson([], ['response' => 'TinyMVC, just a PHP framework based on MVC architecture that helps you <br> build easly and quickly powerful web applications and RESTful API.']);
    }
]);
