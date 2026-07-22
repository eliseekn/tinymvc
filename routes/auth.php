<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

use Core\Http\Routing\Route;

/*
 * Authentication routes
 */

Route::view('/password/forgot', 'auth.password.forgot')->register();
