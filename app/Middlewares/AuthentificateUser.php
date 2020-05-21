<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace App\Middlewares;

use App\Models\UsersModel;
use Framework\Core\Controller;
use Framework\Http\Middleware;
use Framework\Http\Request;

/**
 * AuthentificateUser
 * 
 * Authenficate user when login
 */
class AuthentificateUser extends Middleware
{    
    /**
     * handle
     *
     * @return void
     */
    public function handle()
    {
        $request = new Request();
        $email = $request->postQuery('email');
        $password = $request->postQuery('password');

        $user = new UsersModel();

        if ($user->isRegistered($email, $password)) {
            if (!session_has('logged_user')) {
                create_session('logged_user', $user);
            }
            
            $this->end();
        }

        Controller::redirectToRoute('login');
    }
}