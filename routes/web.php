<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Framework\Routing\View;
use Framework\Http\Response;
use Framework\Routing\Route;
use Framework\Database\DB;

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
        $result = DB::connection('test')->statement('SELECT * FROM users WHERE active = :active', ['active' => 1]);

        Response::send($result->fetchAll(), true);
        //Response::send('Just to test things');
    }
]);