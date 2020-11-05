<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

use Framework\Routing\View;
use Framework\Routing\Route;

/**
 * Password forgot routes
 */

Route::get('password/forgot', [
    'handler' => function() {
        View::render('password/reset');
    }
]);

Route::get('password/reset', ['handler' => 'Auth\PasswordController@reset']);
Route::post('password/notify', ['handler' => 'Auth\PasswordController@notify']);
Route::post('password/new', ['handler' => 'Auth\PasswordController@new']);
