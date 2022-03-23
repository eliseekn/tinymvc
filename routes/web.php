<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Core\Http\Response\JsonResponse;
use Core\Http\Response\Response;
use Core\Routing\Route;

/**
 * Web routes
 */

Route::view('/', 'index')->register();
