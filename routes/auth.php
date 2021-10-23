<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Core\Http\Request;
use Core\Routing\Route;
use Core\Http\Response\Response;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;

/**
 * Authentication routes
 */

Route::groupMiddlewares(['remember'], function () {
    Route::get('login', [LoginController::class, 'index']);
    Route::get('signup', [RegisterController::class, 'index']);
})->register();

Route::groupMiddlewares(['csrf'], function () {
    Route::post('authenticate', [LoginController::class, 'authenticate']);
    Route::post('register', [RegisterController::class, 'register']);
})->register();

Route::post('logout', LogoutController::class)->middlewares('auth')->register();

Route::view('password/forgot', 'auth.password.forgot')->register();

Route::get('password/new', function (Request $request, Response $response) {
	$response->view('auth.password.new', ['email' => $request->queries('email')]);
})->register();

Route::get('password/reset', [ForgotPasswordController::class, 'reset'])->register();
Route::post('password/notify', [ForgotPasswordController::class, 'notify'])->register();
Route::post('password/update', [ForgotPasswordController::class, 'update'])->register();

Route::get('email/verify', [EmailVerificationController::class, 'verify'])->register();
Route::get('email/notify', [EmailVerificationController::class, 'notify'])->register();