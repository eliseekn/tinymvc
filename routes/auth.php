<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

use Framework\Routing\View;
use Framework\Routing\Route;

/**
 * Authentication routes
 */

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

//password reset routes
Route::get('password/forgot', [
    'handler' => function() {
        View::render('auth/password/forgot');
    }
]);

Route::get('password/reset', ['handler' => 'Auth\PasswordController@reset']);
Route::post('password/notify', ['handler' => 'Auth\PasswordController@notify']);
Route::post('password/update', ['handler' => 'Auth\PasswordController@update']);

//email routes
Route::group([
    'confirm' => ['handler' => 'EmailController@confirm'],
    'auth' => ['handler' => 'EmailController@auth'],
])->by([
    'method' => 'GET',
    'prefix' => 'email'
]);
