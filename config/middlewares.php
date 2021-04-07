<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use App\Middlewares\HandleCors;
use App\Middlewares\RememberUser;
use App\Middlewares\AccountPolicy;
use App\Middlewares\AuthPolicy;
use App\Middlewares\CsrfProtection;
use App\Middlewares\SanitizeInputs;
use App\Middlewares\DashboardPolicy;

/**
 * Middlewares configuration
 */

$config = [
    'csrf' => CsrfProtection::class,
    'cors' => HandleCors::class,
    'account' => AccountPolicy::class,
    'dashboard' => DashboardPolicy::class,
    'remember' => RememberUser::class,
    'sanitize' => SanitizeInputs::class,
    'auth' => AuthPolicy::class
];
