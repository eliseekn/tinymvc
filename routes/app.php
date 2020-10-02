<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

use Framework\Routing\View;
use Framework\Routing\Route;

/**
 * Set routes paths
 */

//auth routes
Route::group([
    '/login' => [
        'handler' => function() {
            View::render('auth/login');
        }
    ],

    '/signup' => [
        'handler' => function() {
            View::render('auth/signup');
        }
    ]
])->by([
    'method' => 'GET',
    'middlewares' => [
        'RememberUser',
        'AuthPolicy'
    ]
]);

Route::get('/logout', ['handler' => 'Auth\AuthController@logout']);
Route::post('/authenticate', ['handler' => 'Auth\AuthController@authenticate']);
Route::post('/register', ['handler' => 'Auth\AuthController@register']);

//admin routes
Route::get('/admin', ['handler' => 'Admin\AdminController@index']);
Route::delete('/admin/users/delete/{id:num}', ['handler' => 'Admin\UsersController@delete']);

Route::group([
    '/users' => ['handler' => 'Admin\AdminController@users'],
    '/users/new' => ['handler' => 'Admin\UsersController@new'],
    '/users/edit/{id:num}' => ['handler' => 'Admin\UsersController@edit'],
    '/users/view/{id:num}' => ['handler' => 'Admin\UsersController@view']
])->by([
    'method' => 'GET',
    'prefix' => '/admin',
    'middlewares' => [
        'RememberUser',
        'AdminPolicy'
    ]
]);

Route::group([
    '/users/delete' => ['handler' => 'Admin\UsersController@delete'],
    '/users/import' => ['handler' => 'Admin\UsersController@import'],
    '/users/export' => ['handler' => 'Admin\UsersController@export']
])->by([
    'method' => 'POST',
    'prefix' => '/admin',
    'middlewares' => [
        'RememberUser',
        'AdminPolicy'
    ]
]);

Route::group([
    '/users/create' => ['handler' => 'Admin\UsersController@create'],
    '/users/update/{id:num}' => ['handler' => 'Admin\UsersController@update']
])->by([
    'method' => 'POST',
    'prefix' => '/admin',
    'middlewares' => [
        'RememberUser',
        'CsrfProtection',
        'SanitizeFields',
        'AdminPolicy'
    ]
]);

//password forgot routes
Route::get('/password/forgot', [
    'handler' => function() {
        View::render('password/reset');
    }
]);

Route::get('/password/reset', ['handler' => 'Auth\PasswordResetController@reset']);
Route::post('/password/notify', ['handler' => 'Auth\PasswordResetController@notify']);
Route::post('/password/new', ['handler' => 'Auth\PasswordResetController@new']);

//email confirmation routes
Route::group([
    '/confirmation' => ['handler' => 'EmailConfirmationController@verify'],
    '/confirmation/send' => ['handler' => 'EmailConfirmationController@send']
])->by([
    'method' => 'GET',
    'prefix' => '/email'
]);

//docs routes
Route::get('/docs', [
    'handler' => function() {
        View::render('docs/index');
    }]
);

Route::group([
    '/getting-started' => ['handler' => function() {
        View::render('docs/getting-started');
    }],

    '/routing' => ['handler' => function() {
        View::render('docs/guides/routing');
    }],

    '/middlewares' => ['handler' => function() {
        View::render('docs/guides/middlewares');
    }],

    '/controllers' => ['handler' => function() {
        View::render('docs/guides/controllers');
    }],

    '/views' => ['handler' => function() {
        View::render('docs/guides/views');
    }],

    '/requests' => ['handler' => function() {
        View::render('docs/guides/requests');
    }],

    '/responses' => ['handler' => function() {
        View::render('docs/guides/responses');
    }],

    '/client' => ['handler' => function() {
        View::render('docs/guides/client');
    }],

    '/redirections' => ['handler' => function() {
        View::render('docs/guides/redirections');
    }]
])->by([
    'method' => 'GET',
    'prefix' => '/docs'
]);
