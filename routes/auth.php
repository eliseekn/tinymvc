<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Core\Http\Request;
use Core\Routing\Route;
use Core\Http\Response;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;

/**
 * Authentication routes
 */

Route::group(function () {
    Route::get('/login', [LoginController::class, 'index']);
    Route::get('/signup', [RegisterController::class, 'index']);
})
    ->byMiddleware('remember')
    ->register();

Route::group(function () {
    Route::post('/authenticate', [LoginController::class, 'authenticate']);
    Route::post('/register', [RegisterController::class, 'register']);
})
    ->byMiddleware('csrf')
    ->register();

Route::group(function () {
    Route::group(function () {
        Route::get('/reset', 'reset');

        Route::group(function () {
            Route::post('/notify', 'notify');
            Route::post('/update', 'update');
        })->byMiddleware('csrf');
    })->byController(ForgotPasswordController::class);

    Route::view('/forgot', 'auth.password.forgot');
})
    ->byPrefix('password')
    ->register();

Route::group(function () {
    Route::get('/verify', 'verify');
    Route::get('/notify', 'notify');
})
    ->byPrefix('email')
    ->byController(EmailVerificationController::class)
    ->register();

Route::post('/logout', LogoutController::class)
    ->middleware('auth')
    ->register();
