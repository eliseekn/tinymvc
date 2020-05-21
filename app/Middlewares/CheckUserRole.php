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
 * CheckUserRole
 * 
 * Authenficate user when login
 */
class CheckUserRole extends Middleware
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

        $user = new UsersModel();
        $role = $user->getRole($email);

        if ($role === 'administrator') {
            $this->end();
        }

        Controller::redirectToRoute('posts');
    }
}