<?php

namespace App\Http\Middlewares;

use Core\Support\Auth;
use Core\Http\Request;

/**
 * Authenticate user by http
 */
class HttpAuth
{    
    public function handle(Request $request)
    {
        if (empty($request->getHttpAuth)) {
            response()->json([__('auth_required')], [], 401);
        }

        list($method, $credentials) = $request->getHttpAuth;

        if (trim($method) !== 'Basic') {
            response()->json([__('invalid_auth_method')], [], 401);
        }

        $credentials = base64_decode($credentials);
        list($email, $password) = explode(':', $credentials);

        if (!Auth::checkCredentials($email, $password, $user)) {
            response()->json([__('invalid_credentials')], [], 401);
        }
    }
}
