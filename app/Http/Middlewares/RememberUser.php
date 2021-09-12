<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Middlewares;

use Core\Support\Cookies;
use Core\Support\Session;
use App\Database\Models\User;

/**
 * Check for stored user cookie
 */
class RememberUser
{    
    public function handle()
    {
        if (Cookies::has('user')) {
            $user = User::findBy('email', Cookies::get('user'));

            if ($user !== false && $user->remember) {
                Session::create('user', $user);
            }
        }
    }
}
