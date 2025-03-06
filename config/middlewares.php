<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

/*
 * Middlewares configuration
 */

return [
    'cors' => \App\Http\Middlewares\HttpCors::class,
    'verified' => \App\Http\Middlewares\EmailVerified::class,
    'remember' => \App\Http\Middlewares\RememberUser::class,
    'auth' => \App\Http\Middlewares\Authenticated::class,
    'api' => \App\Http\Middlewares\ApiAuth::class,
    'http' => \App\Http\Middlewares\HttpAuth::class,
    'admin' => \App\Http\Middlewares\CheckUserAdmin::class,
    'redirect' => \App\Http\Middlewares\RedirectIfLogged::class
];
