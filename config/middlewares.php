<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Middlewares configuration
 */

return [
    'csrf' => \App\Http\Middlewares\CsrfProtection::class,
    'cors' => \App\Http\Middlewares\HttpCors::class,
    'account' => \App\Http\Middlewares\AccountPolicy::class,
    'remember' => \App\Http\Middlewares\RememberUser::class,
    'sanitize' => \App\Http\Middlewares\SanitizeInputs::class,
    'auth' => \App\Http\Middlewares\AuthPolicy::class,
    'api_auth' => \App\Http\Middlewares\ApiAuth::class,
    'http_auth' => \App\Http\Middlewares\HttpAuth::class
];
