<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/*
 * Middlewares configuration
 */

return [
    'csrf' => App\Http\Middlewares\CsrfProtection::class,
    'cors' => App\Http\Middlewares\HttpCors::class,
    'verified' => App\Http\Middlewares\EmailVerified::class,
    'remember' => App\Http\Middlewares\RememberUser::class,
    'sanitize' => App\Http\Middlewares\SanitizeInputs::class,
    'auth' => App\Http\Middlewares\AuthPolicy::class,
    'api' => App\Http\Middlewares\ApiAuth::class,
    'http' => App\Http\Middlewares\HttpAuth::class,
    'admin' => App\Http\Middlewares\CheckUserAdmin::class,
];
