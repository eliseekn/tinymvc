<?php

namespace App\Http\Middlewares;

use Framework\Http\Redirect;

/**
 * CheckSessionToLogin
 * 
 * Check for user logged session
 */
class CheckSessionToLogin
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
