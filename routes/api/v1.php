<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

use App\Http\Controllers\Api\v1\AuthController;
use Core\Http\Routing\Route;

/*
 * API routes
 */

Route::group(function () {
    Route::group(function () {
        Route::post('/login', 'login');
        Route::post('/logout', 'logout')->middleware('api');
    })->byController(AuthController::class);
})
    ->byPrefix('/api/v1')
    ->register();
