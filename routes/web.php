<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use App\Database\Models\UsersModel;
use Carbon\Carbon;
use Framework\HTTP\Client;
use Framework\Routing\View;
use Framework\HTTP\Response;
use Framework\Routing\Route;
use Framework\Support\Session;

/**
 * Web routes
 */

Route::get('/', [
    'handler' => function() {
        View::render('index');
    }
]);

Route::get('/home', [
    'handler' => 'HomeController@index'
]);

Route::get('test', [
    'handler' => function() {
        Session::close('user');
        //dd(Carbon::now()->toTimeString());
        //Response::json(UsersModel::select()->orderAsc()->first());
    }
]);