<?php

namespace App\Http\Middlewares;

use Framework\System\Cookies;
use Framework\System\Session;
use App\Database\Repositories\Users;

/**
 * Check for user cookie
 */
class RememberUser
{    
    /**
     * handle function
     *
     * @param  \App\Database\Repositories\Users $users
     * @return void
     */
    public function handle(Users $users): void
    {
        if (Cookies::has('user')) {
            $user = $users->findOneByEmail(Cookies::get('user'));

            if ($user !== false && $user->remember) {
                Session::create('user', $user);
            }
        }
    }
}
