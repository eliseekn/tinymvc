<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Middlewares\ApiAuth;
use Core\Http\Routing\Route;

/*
 * API routes
 */

Route::group(fn () => Route::group(function () {
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware(ApiAuth::class);
    Route::post('/register', 'register');
})->byController(AuthController::class))
    ->byPrefix('/api/v1')
    ->register();
