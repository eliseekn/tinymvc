<?php

namespace App\Middlewares;

use App\Helpers\Auth;
use Framework\Http\Request;
use Framework\Support\Cookies;
use Framework\Support\Session;
use App\Database\Models\UsersModel;

/**
 * Check for user cookie
 */
class RememberUser
{    
    /**
     * handle function
     *
     * @return void
     */
    public static function handle(Request $request): void
    {
        if (Auth::remember()) {
            $user = UsersModel::findSingleBy('email', Cookies::get('user'));

            if ($user !== false) {
                Session::create('user', $user);
            }
        }
    }
}
