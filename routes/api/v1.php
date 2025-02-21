<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use App\Http\Controllers\Api\v1\AuthController;
use Core\Routing\Route;

/*
 * API routes
 */

Route::group(function () {
    Route::group(function () {
        Route::post('/login', 'login');
        Route::post('/logout', 'logout')->middleware('auth');
    })->byController(AuthController::class);
})
    ->byPrefix('api/v1')
    ->register();
