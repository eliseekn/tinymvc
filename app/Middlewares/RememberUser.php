<?php

namespace App\Middlewares;

use App\Helpers\AuthHelper;
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
            $user = UsersModel::findWhere('email', Encryption::decrypt(get_user_cookie()));

            if (!empty($user)) {
                create_user_session($user);
            }
        }
        
    }
}
