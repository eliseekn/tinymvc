<?php

namespace App\Middlewares;

use App\Helpers\Auth;
use Framework\Http\Request;
use Framework\Database\Model;
use Framework\Support\Cookies;
use Framework\Support\Session;

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
            $user = (new Model('users'))->findSingleBy('email', Cookies::get('user'));

            if ($user !== false) {
                Session::create('user', $user);
            }
        }
    }
}
