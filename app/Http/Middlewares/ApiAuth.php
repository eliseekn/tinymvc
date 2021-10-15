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
use Core\Support\Encryption;

/**
 * Authenticate user by api token
 */
class ApiAuth
{   
    public function handle(Request $request, JsonResponse $response)
    {
        if (empty($request->getHttpAuth())) {
            $response->send(__('auth_required'), [], 401);
        }

        list($method, $token) = $request->getHttpAuth();

        if (trim($method) !== 'Bearer') {
            $response->send(__('invalid_auth_method'), [], 401);
        }

        if (!Auth::checkToken(Encryption::decrypt($token), $user)) {
            $response->send(__('invalid_credentials'), [], 401);
        }
    }
}
