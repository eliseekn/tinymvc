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

//home routes
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

//post routes
Route::add('/post/{slug:str}', [
    'method' => 'GET',
    'controller' => 'PostController@index',
    'name' => 'post'
]);

Route::add('/post/add', [
    'method' => 'POST',
    'controller' => 'PostController@add',
    'name' => 'post_add',
    'middlewares' => ['sanitize_input']
]);

Route::add('/post/edit/{id:int}', [
    'method' => 'POST',
    'controller' => 'PostController@edit',
    'name' => 'post_edit',
    'middlewares' => ['sanitize_input']
]);

Route::add('/post/replaceImage/{postId:int}', [
    'method' => 'POST',
    'controller' => 'PostController@replaceImage',
    'name' => 'post_edit'
]);

Route::add('/post/delete/{id:int}', [
    'method' => 'GET',
    'controller' => 'PostController@delete',
    'name' => 'poost_delete'
]);

//admin routes
Route::add('/admin', [
    'method' => 'GET',
    'controller' => 'AdminController@posts',
    'name' => 'admin_posts',
    'middlewares' => ['admin_session']
]);

Route::add('/admin/posts', [
    'method' => 'GET',
    'controller' => 'AdminController@posts',
    'name' => 'admin_posts',
    'middlewares' => ['admin_session']
]);

Route::add('/admin/comments', [
    'method' => 'GET',
    'controller' => 'AdminController@comments',
    'name' => 'admin_comments',
    'middlewares' => ['admin_session']
]);

//user routes
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
        'sanitize_input',  
        'auth_validator'
    ]
]);

Route::add('/user/logout', [
    'method' => 'GET',
    'controller' => 'UserController@logout',
    'name' => 'logout'
]);

//comments routes
Route::add('/comment/add/{postId:int}', [
    'method' => 'POST',
    'controller' => 'CommentController@add',
    'name' => 'comment_add',
    'middlewares' => [
        'sanitize_input',  
        'comment_validator'
    ]
]);

Route::add('/comment/delete/{id:int}', [
    'method' => 'GET',
    'controller' => 'CommentController@delete',
    'name' => 'comment_delete'
]);