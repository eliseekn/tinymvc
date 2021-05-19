<?php

namespace App\Http\Middlewares;

use Core\System\Auth;
use Core\Http\Request;
use App\Database\Repositories\Users;

/**
 * Authenticated user api
 */
class HttpAuth
{    
    /**
     * handle function
     *
     * @param  \Core\Http\Request $request
     * @param  \App\Database\Repositories\Users $users
     * @return void
     */
    public function handle(Request $request, Users $users): void
    {
        if (empty($request->http_auth())) {
            response()->json(__('auth_required'), [], 401);
        }

        list($method, $credentials) = $request->http_auth();

        if (trim($method) !== 'Basic') {
            response()->json(__('invalid_auth_method'), [], 401);
        }

        $credentials = base64_decode($credentials);
        list($email, $password) = explode(':', $credentials);

        if (!Auth::checkCredentials($users, $email, $password, $user)) {
            response()->json(__('invalid_credentials'), [], 401);
        }
    }
}
