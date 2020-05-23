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
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->setName('home');
Route::get('/login', 'UserController@index')->setName('login.page')->useMiddlewares(['login']);
Route::post('/user/login', 'UserController@login')->setName('user.login')->useMiddlewares(['csrf']);
Route::get('/user/logout', 'UserController@logout')->setName('user.logout');
Route::get('/admin', 'AdminController@index')->setName('admin')->useMiddlewares(['admin']);
