<?php

namespace App\Middlewares;

use App\Database\Models\UsersModel;
use Framework\Http\Redirect;
use Framework\Http\Response;
use Framework\Support\Authenticate;
use Framework\Support\Encryption;

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
    public static function handle()
    {
        if (Authenticate::checkRemember()) {
            $user = UsersModel::findWhere('email', Encryption::decrypt(get_cookie('user')));

            if (!empty($user)) {
                create_session('user', $user);
            }
        }
        
    }
}
