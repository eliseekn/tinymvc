<?php

namespace App\Middlewares;

use App\Helpers\AuthHelper;
use Framework\Support\Cookies;
use Framework\Support\Session;
use Framework\Support\Encryption;
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
        if (AuthHelper::checkCookie()) {
            $user = UsersModel::find('email', Encryption::decrypt(Cookies::getUser()))->single();

            if ($user !== false) {
                Session::getUser($user);
            }
        }
        
    }
}
