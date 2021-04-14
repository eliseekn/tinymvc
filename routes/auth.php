<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Framework\Routing\View;
use Framework\Routing\Route;
use App\Controllers\Auth\AuthController;
use App\Controllers\Auth\EmailController;
use App\Controllers\Auth\PasswordController;

/**
 * Authentication routes
 */

Route::groupMiddlewares(['remember'], function () {
    Route::get('login', function () {
        View::render('auth.login');
    });

    Route::get('signup', function () {
        View::render('auth.signup');
    });
})->register();

Route::get('logout', [AuthController::class, 'logout'])->register();
Route::post('authenticate', [AuthController::class, 'authenticate'])->register();
Route::post('register', [AuthController::class, 'register'])->register();

Route::get('password/forgot', function() {
    View::render('auth.password.forgot');
})->register();

Route::get('password/reset', [PasswordController::class, 'reset'])->register();
Route::post('password/notify', [PasswordController::class, 'notify'])->register();
Route::post('password/update', [PasswordController::class, 'update'])->register();

Route::groupPrefix('email', function () {
    Route::get('confirm', [EmailController::class, 'confirm']);
    Route::get('auth', [EmailController::class, 'auth']);
})->register();