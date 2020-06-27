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

use Framework\Routing\Route;
use Framework\Routing\View;

/**
 * Set routes paths
 */

Route::get('/', [
    'handler' => function() {
        View::render('index');
    }
]);

Route::get('/admin', [
    'handler' => 'Admin\AdminController@index',
    'name' => 'admin',
    'middlewares' => [
        'csrf',
        'sanitize',
        'auth',
        'admin'
    ]
]);