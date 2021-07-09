<?php

namespace App\Http\Middlewares;

use Core\Support\Auth;
use Core\Http\Request;
use Core\Support\Encryption;
use App\Database\Repositories\UserRepository;
use App\Database\Repositories\TokenRepository;

/**
 * Authenticate user by api token
 */
class ApiAuth
{   
    public function handle(Request $request, UserRepository $userRepository, TokenRepository $tokenRepository)
    {
        if (empty($request->getHttpAuth)) {
            response()->json(__('auth_required'), [], 401);
        }

        list($method, $token) = $request->getHttpAuth();

        if (trim($method) !== 'Bearer') {
            response()->json(__('invalid_auth_method'), [], 401);
        }

        if (!Auth::checkToken($userRepository, $tokenRepository, Encryption::decrypt($token), $user)) {
            response()->json(__('invalid_credentials'), [], 401);
        }
    }
}
