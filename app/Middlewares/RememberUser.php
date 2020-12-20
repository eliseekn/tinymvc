<?php

namespace App\Middlewares;

use App\Helpers\Auth;
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
    public static function handle(): void
    {
        if (Auth::remember()) {
            $user = UsersModel::findBy('email', Cookies::get('user'))->single();

            if ($user !== false) {
                Session::create('user', $user);
            }
        }
        
    }
}
