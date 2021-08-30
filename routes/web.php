<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use App\Database\Models\User;
use Core\Routing\Route;

/**
 * Web routes
 */

Route::view('/', 'index')->register();

Route::get('/test', function () {

    $user = User::all();

    dd($user);
})->register();