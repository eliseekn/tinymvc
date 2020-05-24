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

Route::add('/', [
    'method' => 'GET',
    'controller' => 'HomeController@index',
    'name' => 'home'
]);

Route::add('/home', [
    'method' => 'GET',
    'controller' => 'HomeController@index',
    'name' => 'home'
]);

Route::add('/login', [
    'method' => 'GET',
    'controller' => 'UserController@index',
    'name' => 'auth_page',
    'middlewares' => ['auth_session']
]);

Route::add('/user/login', [
    'method' => 'POST',
    'controller' => 'UserController@login',
    'name' => 'auth_action',
    'middlewares' => [
        'csrf_validator', 
        'auth_validator'
    ]
]);

Route::add('/user/logout', [
    'method' => 'GET',
    'controller' => 'UserController@logout',
    'name' => 'logout'
]);

Route::add('/admin', [
    'method' => 'GET',
    'controller' => 'AdminController@index',
    'name' => 'admin',
    'middlewares' => ['admin_session']
]);

Route::add('/post/{slug:str}', [
    'method' => 'GET',
    'controller' => 'PostController@index',
    'name' => 'post'
]);
