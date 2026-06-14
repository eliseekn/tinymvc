<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Middlewares;

use Core\Enums\HttpAuthMethod;
use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;
use Core\Http\Auth;
use Core\Http\Response\JsonResponse;

/**
 * Authenticate user by api token.
 */
class ApiAuth
{
    public function handle(): void
    {
        if (empty(request()->getHttpAuth())) {
            new JsonResponse([
                'status' => ResponseStatus::ERROR,
                'message' => __('alert.auth_required'),
            ], HttpCode::UNAUTHORIZED)->send();
        }

        [$method, $token] = request()->getHttpAuth();

        if (trim($method) !== HttpAuthMethod::BEARER) {
            new JsonResponse([
                'status' => ResponseStatus::ERROR,
                'message' => __('alert.invalid_auth_method'),
            ], HttpCode::BAD_REQUEST)->send();
        }

        if (! Auth::checkToken(decrypt($token), $user)) {
            new JsonResponse([
                'status' => ResponseStatus::ERROR,
                'message' => __('alert.invalid_credentials'),
            ], HttpCode::UNAUTHORIZED)->send();
        }
    }
}
