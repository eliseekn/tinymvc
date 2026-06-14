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
 * Authenticate user by http.
 */
class HttpAuth
{
    public function handle(): void
    {
        if (empty(request()->getHttpAuth())) {
            new JsonResponse([
                'status' => ResponseStatus::ERROR,
                'message' => __('alert.auth_required'),
            ], HttpCode::UNAUTHORIZED)->send();
        }

        [$method, $credentials] = request()->getHttpAuth();

        if (trim($method) !== HttpAuthMethod::BASIC) {
            new JsonResponse([
                'status' => ResponseStatus::ERROR,
                'message' => __('alert.invalid_auth_method'),
            ], HttpCode::BAD_REQUEST)->send();
        }

        $credentials = base64_decode($credentials);
        [$email, $password] = explode(':', $credentials);

        if (! Auth::checkCredentials($email, $password, $user)) {
            new JsonResponse([
                'status' => ResponseStatus::ERROR,
                'message' => __('alert.invalid_credentials'),
            ], HttpCode::UNAUTHORIZED)->send();
        }
    }
}
