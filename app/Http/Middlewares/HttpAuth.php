<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Middlewares;

use Core\Enums\HttpCode;
use Core\Http\Auth;
use Core\Http\Request;
use Core\Http\Response;

/**
 * Authenticate user by http.
 */
class HttpAuth
{
    public function handle(Request $request, Response $response): void
    {
        if (empty($request->getHttpAuth())) {
            $response->json([__('auth_required')])->send(HttpCode::UNAUTHORIZED);
        }

        list($method, $credentials) = $request->getHttpAuth();

        if (trim($method) !== 'Basic') {
            $response->json([__('invalid_auth_method')])->send(HttpCode::BAD_REQUEST);
        }

        $credentials = base64_decode($credentials);
        list($email, $password) = explode(':', $credentials);

        if (! Auth::checkCredentials($email, $password, $user)) {
            $response->json([__('invalid_credentials')])->send(HttpCode::UNAUTHORIZED);
        }
    }
}
