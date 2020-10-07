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
    'login' => [
        'handler' => function() {
            View::render('auth/login');
        }
    ],

    'signup' => [
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

Route::get('logout', ['handler' => 'Auth\AuthController@logout']);
Route::post('authenticate', ['handler' => 'Auth\AuthController@authenticate']);
Route::post('register', ['handler' => 'Auth\AuthController@register']);

//admin routes
Route::group([
    'dashboard' => [
        'method' => 'GET',
        'handler' => 'Admin\AdminController@index'
    ],

    'users/delete/{id:num}' => [
        'method' => 'DELETE',
        'handler' => 'Admin\UsersController@delete'
    ],

    'roles/delete/{id:num}' => [
        'method' => 'DELETE',
        'handler' => 'Admin\RolesController@delete'
    ]
])->by([
    'prefix' => 'admin',
    'middlewares' => [
        'RememberUser',
        'AdminPolicy'
    ]
]);

Route::group([
    'users' => ['handler' => 'Admin\AdminController@users'],
    'users/new' => ['handler' => 'Admin\UsersController@new'],
    'users/edit/{id:num}' => ['handler' => 'Admin\UsersController@edit'],
    'users/view/{id:num}' => ['handler' => 'Admin\UsersController@view'],

    'roles' => ['handler' => 'Admin\AdminController@roles'],
    'roles/new' => ['handler' => 'Admin\RolesController@new'],
    'roles/edit/{id:num}' => ['handler' => 'Admin\RolesController@edit'],
    'roles/view/{id:num}' => ['handler' => 'Admin\RolesController@view']
])->by([
    'method' => 'GET',
    'prefix' => 'admin',
    'middlewares' => [
        'RememberUser',
        'AdminPolicy'
    ]
]);

Route::group([
    'users/delete' => ['handler' => 'Admin\UsersController@delete'],
    'users/import' => ['handler' => 'Admin\UsersController@import'],
    'users/export' => ['handler' => 'Admin\UsersController@export'],

    'roles/delete' => ['handler' => 'Admin\RolesController@delete'],
    'roles/import' => ['handler' => 'Admin\RolesController@import'],
    'roles/export' => ['handler' => 'Admin\RolesController@export']
])->by([
    'method' => 'POST',
    'prefix' => 'admin',
    'middlewares' => [
        'RememberUser',
        'AdminPolicy'
    ]
]);

Route::group([
    'users/create' => ['handler' => 'Admin\UsersController@create'],
    'users/update/{id:num}' => ['handler' => 'Admin\UsersController@update'],

    'roles/create' => ['handler' => 'Admin\RolesController@create'],
    'roles/update/{id:num}' => ['handler' => 'Admin\RolesController@update']
])->by([
    'method' => 'POST',
    'prefix' => 'admin',
    'middlewares' => [
        'RememberUser',
        'CsrfProtection',
        'SanitizeFields',
        'AdminPolicy'
    ]
]);

//password forgot routes
Route::group([
    'forgot' => [
        'method' => 'GET',
        'handler' => function() {
            View::render('password/reset');
        }
    ],

    'reset' => [
        'method' => 'GET',
        'handler' => 'Auth\PasswordController@reset'
    ],

    'notify' => [
        'method' => 'POST',
        'handler' => 'Auth\PasswordController@notify'
    ],

    'new' => [
        'method' => 'POST',
        'handler' => 'Auth\PasswordController@new'
    ]
])->by([
    'prefix' => 'password'
]);

//email confirmation routes
Route::group([
    'confirmation' => ['handler' => 'EmailController@verify'],
    'confirmation/notify' => ['handler' => 'EmailController@notify']
])->by([
    'method' => 'GET',
    'prefix' => 'email'
]);

//docs routes
Route::get('docs', [
    'handler' => function() {
        View::render('docs/index');
    }]
);

Route::group([
    'getting-started' => ['handler' => function() {
        View::render('docs/getting-started');
    }],

    'routing' => ['handler' => function() {
        View::render('docs/guides/routing');
    }],

    'middlewares' => ['handler' => function() {
        View::render('docs/guides/middlewares');
    }],

    'controllers' => ['handler' => function() {
        View::render('docs/guides/controllers');
    }],

    'views' => ['handler' => function() {
        View::render('docs/guides/views');
    }],

    'requests' => ['handler' => function() {
        View::render('docs/guides/requests');
    }],

    'responses' => ['handler' => function() {
        View::render('docs/guides/responses');
    }],

    'client' => ['handler' => function() {
        View::render('docs/guides/client');
    }],

    'redirections' => ['handler' => function() {
        View::render('docs/guides/redirections');
    }]
])->by([
    'method' => 'GET',
    'prefix' => 'docs'
]);
