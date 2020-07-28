<?php

namespace App\Middlewares;

use Framework\Support\Encryption;
use App\Database\Models\UsersModel;
use Framework\Support\Authenticate;

/**
 * RememberUser
 * 
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
        if (Authenticate::checkRemember()) {
            $user = UsersModel::findWhere('email', Encryption::decrypt(get_user_cookie()));

            if (!empty($user)) {
                create_user_session($user);
            }
        }
        
    }
}
