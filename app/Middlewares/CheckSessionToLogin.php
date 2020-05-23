<?php

namespace App\Middlewares;

use Framework\Http\Redirect;
use Framework\Core\Middleware;

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
            Redirect::toRoute('admin')->only();
        }
    }
}
