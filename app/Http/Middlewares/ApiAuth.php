<?php

namespace App\Http\Middlewares;

use App\Helpers\Auth;
use Framework\Http\Request;
use Framework\System\Encryption;
use App\Database\Repositories\Users;
use App\Database\Repositories\Tokens;

/**
 * Authenticated user api
 */
class ApiAuth
{    
    /**
     * handle function
     *
     * @param  \Framework\Http\Request $request
     * @param  \App\Database\Repositories\Users $users
     * @param  \App\Database\Repositories\Tokens $tokens
     * @return void
     */
    public function handle(Request $request, Users $users, Tokens $tokens): void
    {
        if (empty($request->http_auth())) {
            response()->json(['Authorization Required'], [], 401);
        }

        list($method, $token) = $request->http_auth();

        if (trim($method) !== 'Bearer') {
            response()->json(['Invalid authentication method'], [], 401);
        }

        if (!Auth::checkByToken($users, $tokens, Encryption::decrypt($token), $user)) {
            response()->json(['Invalid credentials'], [], 401);
        }
    }
}
