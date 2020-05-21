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

use Framework\Http\Route;

/**
 * Set routes paths
 */
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->setName('home')->setMiddlewares(['csrf', 'auth']);
Route::get('/post/add', 'PostsController@add')->setName('post.add')->setMiddlewares(['auth', 'role']);