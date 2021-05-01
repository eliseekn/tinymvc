<?php

namespace App\Http\Middlewares;

use App\Helpers\Auth;
use Framework\Http\Request;
use App\Database\Repositories\Users;

/**
 * Authenticated user api
 */
class HttpAuth
{    
    /**
     * handle function
     *
     * @param  \Framework\Http\Request $request
     * @param  \App\Database\Repositories\Users $users
     * @return void
     */
    public function handle(Request $request, Users $users): void
    {
        if (empty($request->http_auth())) {
            response()->json(['Authorization Required'], [], 401);
        }

        list($method, $credentials) = $request->http_auth();

        if (trim($method) !== 'Basic') {
            response()->json(['Invalid authentication method'], [], 401);
        }

        $credentials = base64_decode($credentials);
        list($email, $password) = explode(':', $credentials);

        if (!Auth::checkByCredentials($users, $email, $password, $user)) {
            response()->json(['Invalid credentials'], [], 401);
        }
    }
}
