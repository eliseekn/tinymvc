<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

use Framework\HTTP\Response;
use Framework\Routing\View;
use Framework\Routing\Route;
use PragmaRX\Countries\Package\Countries;

/**
 * Web routes
 */

Route::get('/', [
    'handler' => function() {
        View::render('index');
    }
]);

Route::get('/home', [
    'handler' => 'HomeController@index'
]);
