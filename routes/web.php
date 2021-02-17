<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use App\Database\Models\UsersModel;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Routing\Route;
use Framework\Routing\View;

/**
 * Web routes
 */

Route::get('/', ['handler' => 'HomeController@index']);

Route::get('test', [
    'handler' => function () {
        dd(UsersModel::findMany(['email' => 'admin@mail.com', 'phone' => '00000000'], 'and')->exists());
    }
]);