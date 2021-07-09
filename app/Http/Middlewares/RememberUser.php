<?php

namespace App\Http\Middlewares;

use Core\Support\Cookies;
use Core\Support\Session;
use App\Database\Repositories\UserRepository;

/**
 * Check for user cookie
 */
class RememberUser
{    
    public function handle(UserRepository $userRepository)
    {
        if (Cookies::has('user')) {
            $user = $userRepository->findByEmail(Cookies::get('user'));

            if ($user !== false && $user->remember) {
                Session::create('user', $user);
            }
        }
    }
}
