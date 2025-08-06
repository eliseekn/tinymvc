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
 * Authenticate user by http.
 */
class HttpAuth
{
    public function handle(): void
    {
        if (empty(request()->getHttpAuth())) {
            response()->json([
                'status' => ResponseStatus::ERROR,
                'message' => __('alert.auth_required'),
            ])->send(HttpCode::UNAUTHORIZED);
        }

        [$method, $credentials] = request()->getHttpAuth();

        if (trim($method) !== HttpAuthMethod::BASIC) {
            response()->json([
                'status' => ResponseStatus::ERROR,
                'message' => __('alert.invalid_auth_method'),
            ])->send(HttpCode::BAD_REQUEST);
        }

        $credentials = base64_decode($credentials);
        [$email, $password] = explode(':', $credentials);

        if (! Auth::checkCredentials($email, $password, $user)) {
            response()->json([
                'status' => ResponseStatus::ERROR,
                'message' => __('alert.invalid_credentials'),
            ])->send(HttpCode::UNAUTHORIZED);
        }
    }
}
