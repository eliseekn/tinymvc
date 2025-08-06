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

/**
 * Authenticate user by api token.
 */
class ApiAuth
{
    public function handle(): void
    {
        if (empty(request()->getHttpAuth())) {
            response()->json([
                'status' => ResponseStatus::ERROR,
                'message' => __('alert.auth_required'),
            ])->send(HttpCode::UNAUTHORIZED);
        }

        [$method, $token] = request()->getHttpAuth();

        if (trim($method) !== HttpAuthMethod::BEARER) {
            response()->json([
                'status' => ResponseStatus::ERROR,
                'message' => __('alert.invalid_auth_method'),
            ])->send(HttpCode::BAD_REQUEST);
        }

        if (! Auth::checkToken(decrypt($token), $user)) {
            response()->json([
                'status' => ResponseStatus::ERROR,
                'message' => __('alert.invalid_credentials'),
            ])->send(HttpCode::UNAUTHORIZED);
        }
    }
}
