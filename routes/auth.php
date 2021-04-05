<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Framework\Routing\View;
use Framework\Routing\Route;
use App\Middlewares\AuthPolicy;
use App\Middlewares\RememberUser;
use App\Controllers\Auth\AuthController;
use App\Controllers\Auth\EmailController;
use App\Controllers\Auth\PasswordController;

/**
 * Authentication routes
 */

Route::group([
    'login' => [
        'handler' => function() {
            View::render('auth.login');
        }
    ],

    'signup' => [
        'handler' => function() {
            View::render('auth.signup');
        }
    ]
])->by([
    'method' => 'GET',
    'middlewares' => [
        RememberUser::class, 
        AuthPolicy::class
    ]
]);

Route::get('logout', ['handler' => [AuthController::class, 'logout']]);
Route::post('authenticate', ['handler' => [AuthController::class, 'authenticate']]);
Route::post('register', ['handler' => [AuthController::class, 'register']]);

//password reset routes
Route::get('password/forgot', [
    'handler' => function() {
        View::render('auth.password.forgot');
    }
]);

Route::get('password/reset', ['handler' => [PasswordController::class, 'reset']]);
Route::post('password/notify', ['handler' => [PasswordController::class, 'notify']]);
Route::post('password/update', ['handler' => [PasswordController::class, 'update']]);

//email routes
Route::group([
    'confirm' => ['handler' => [EmailController::class, 'confirm']],
    'auth' => ['handler' => [EmailController::class, 'auth']]
])->by([
    'method' => 'GET',
    'prefix' => 'email'
]);
