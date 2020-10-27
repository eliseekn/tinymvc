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
    'confirmation' => ['handler' => 'EmailController@verify'],
    'confirmation/notify' => ['handler' => 'EmailController@notify']
])->by([
    'method' => 'GET',
    'prefix' => 'email'
]);
