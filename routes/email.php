<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

use Framework\Routing\Route;

/**
 * Set email confirmation routes
 */

Route::group([
    'confirm' => ['handler' => 'EmailController@verify'],
    'confirmation/notify' => ['handler' => 'EmailController@notify'],
    'auth' => ['handler' => 'EmailController@auth'],
])->by([
    'method' => 'GET',
    'prefix' => 'email'
]);
