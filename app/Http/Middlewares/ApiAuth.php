<?php

namespace App\Http\Middlewares;

use Core\System\Auth;
use Core\Http\Request;
use Core\System\Encryption;
use App\Database\Repositories\Users;
use App\Database\Repositories\Tokens;

/**
 * Authenticate user by api token
 */
class ApiAuth
{    
    /**
     * handle function
     *
     * @param  \Core\Http\Request $request
     * @param  \App\Database\Repositories\Users $users
     * @param  \App\Database\Repositories\Tokens $tokens
     * @return void
     */
    public function handle(Request $request, Users $users, Tokens $tokens): void
    {
        if (empty($request->http_auth())) {
            response()->json(__('auth_required'), [], 401);
        }

        list($method, $token) = $request->http_auth();

        if (trim($method) !== 'Bearer') {
            response()->json(__('invalid_auth_method'), [], 401);
        }

        if (!Auth::checkToken($users, $tokens, Encryption::decrypt($token), $user)) {
            response()->json(__('invalid_credentials'), [], 401);
        }
    }
}
