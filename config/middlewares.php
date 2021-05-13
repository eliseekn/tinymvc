<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use App\Http\Middlewares\ApiAuth;
use App\Http\Middlewares\HttpCors;
use App\Http\Middlewares\HttpAuth;
use App\Http\Middlewares\AuthPolicy;
use App\Http\Middlewares\RememberUser;
use App\Http\Middlewares\AccountPolicy;
use App\Http\Middlewares\CsrfProtection;
use App\Http\Middlewares\SanitizeInputs;

/**
 * Middlewares configuration
 */

return [
    'csrf' => CsrfProtection::class,
    'cors' => HttpCors::class,
    'account' => AccountPolicy::class,
    'remember' => RememberUser::class,
    'sanitize' => SanitizeInputs::class,
    'auth' => AuthPolicy::class,
    'api_auth' => ApiAuth::class,
    'http_auth' => HttpAuth::class
];
