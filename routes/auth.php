<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Core\Routing\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;

/**
 * Authentication routes
 */

Route::groupMiddlewares(['remember'], function () {
    Route::get('login', [AuthController::class, 'login']);
    Route::get('signup', [AuthController::class, 'signup']);
})->register();

Route::groupMiddlewares(['csrf'], function () {
    Route::post('authenticate', [AuthController::class, 'authenticate']);
    Route::post('register', [AuthController::class, 'register']);
})->register();

Route::get('logout', [AuthController::class, 'logout'])->register();

Route::view('password/forgot', 'auth.password.forgot')->register();
Route::get('password/reset', [ForgotPasswordController::class, 'reset'])->register();
Route::post('password/notify', [ForgotPasswordController::class, 'notify'])->register();
Route::post('password/update', [ForgotPasswordController::class, 'update'])->register();

Route::get('email/verify', [EmailVerificationController::class, 'verify'])->register();