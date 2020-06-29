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
    'handler' => function() {
        View::render('admin/index');
    },
    'middlewares' => [
        'remember',
        'admin'
    ]
]);

Route::get('/admin/login', [
    'handler' => function() {
        View::render('admin/login');
    },
    'middlewares' => [
        'remember',
        'auth'
    ]
]);

Route::get('/admin/logout', [
    'handler' => 'Admin\AdminController@logout'
]);

Route::post('/admin/authenticate', [
    'handler' => 'Admin\AdminController@authenticate'
]);

Route::get('/admin/users/add', [
    'handler' => function() {
        View::render('admin/users/add');
    },
    'middlewares' => [
        'admin'
    ]
]);

Route::get('/admin/users', [
    'handler' => 'Admin\AdminController@users',
    'middlewares' => [
        'admin'
    ]
]);

Route::get('/admin/users/edit/{id:num}', [
    'handler' => 'Admin\UsersController@edit',
    'middlewares' => [
        'admin'
    ]
]);

Route::post('/admin/users/create', [
    'handler' => 'Admin\UsersController@create',
    'middlewares' => [
        'csrf',
        'sanitize',
        'admin'
    ]
]);

Route::post('/admin/users/update/{id:num}', [
    'handler' => 'Admin\UsersController@update',
    'middlewares' => [
        'csrf',
        'sanitize',
        'admin'
    ]
]);

Route::get('/admin/users/delete/{id:num}', [
    'handler' => 'Admin\UsersController@delete',
    'middlewares' => [
        'admin'
    ]
]);

Route::get('/password/forgot', [
    'handler' => function() {
        View::render('password/forgot');
    }
]);

Route::get('/password/reset/{token:any}', [
    'handler' => function() {
        View::render('password/reset');
    }
]);

Route::get('/password/notify', [
    'handler' => 'PasswordResetController@notify'
]);

Route::post('/password/new', [
    'handler' => 'PasswordResetController@new'
]);