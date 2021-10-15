<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Middlewares;

use Core\Support\Auth;
use Core\Http\Request;
use Core\Http\Response\JsonResponse;

/**
 * Authenticate user by http
 */
class HttpAuth
{    
    public function handle(Request $request, JsonResponse $response)
    {
        if (empty($request->getHttpAuth())) {
            $response->send(__('auth_required'), [], 401);
        }

        list($method, $credentials) = $request->getHttpAuth();

        if (trim($method) !== 'Basic') {
            $response->send(__('invalid_auth_method'), [], 401);
        }

        $credentials = base64_decode($credentials);
        list($email, $password) = explode(':', $credentials);

        if (!Auth::checkCredentials($email, $password, $user)) {
            $response->send(__('invalid_credentials'), [], 401);
        }
    }
}
