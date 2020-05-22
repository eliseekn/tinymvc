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

use Framework\Http\Router;
use Framework\Http\Middleware;

/**
 * CheckSessionToLogin
 * 
 * Check for user logged session
 */
class CheckSessionToLogin extends Middleware
{    
    /**
     * handle function
     *
     * @return void
     */
    public function handle()
    {
        $user = get_session('logged_user');

        if (!empty($user)) {
            Router::redirectToRoute('admin');
        }
    }
}
