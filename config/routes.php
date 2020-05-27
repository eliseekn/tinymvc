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
Route::group([
    '/' => [],
    '/home' => []
])->by([
    'method' => 'GET',
    'controller' => 'HomeController@index',
    'name' => 'home'
]);

//admin routes
Route::group([
    '/admin' => [],
    '/admin/posts' => []
])->by([
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

//post routes
Route::group([
    '/post/{slug:str}' => [
        'controller' => 'PostController@index'
    ],
    '/post/delete/{id:int}' => [
        'controller' => 'PostController@delete'
    ],
])->by([
    'method' => 'GET'
]);

Route::group([
    '/post/add' => [
        'controller' => 'PostController@add',
    ],
    '/post/edit/{id:int}' => [
        'controller' => 'PostController@edit'
    ]
])->by([
    'method' => 'POST',
    'middlewares' => ['sanitize_input']
]);

Route::add('/post/replaceImage/{postId:int}', [
    'method' => 'POST',
    'controller' => 'PostController@replaceImage'
]);

//user routes
Route::group([
    '/login' => [
        'controller' => 'UserController@index',
        'name' => 'auth_page',
        'middlewares' => ['auth_session']
    ],
    '/user/logout'=> [
        'controller' => 'UserController@logout'
    ]
])->by([
    'method' => 'GET'
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

//comments routes
Route::add('/comment/add/{postId:int}', [
    'method' => 'POST',
    'controller' => 'CommentController@add',
    'middlewares' => [
        'sanitize_input',  
        'comment_validator'
    ]
]);

Route::add('/comment/delete/{id:int}', [
    'method' => 'GET',
    'controller' => 'CommentController@delete'
]);