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

use Framework\Core\Route;

/**
 * Set routes paths
 */

Route::group([
    '/' => [],
    '/home' => []
])->by([
    'method' => 'GET',
    'controller' => 'HomeController@index',
    'name' => 'home'
]);
