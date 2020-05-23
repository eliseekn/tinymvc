<?php

namespace App\Middlewares;

use Framework\Http\Redirect;

/**
 * CheckSessionToAdmin
 * 
 * Check for user with administrator role session
 */
class CheckSessionToAdmin
{    
    /**
     * handle function
     *
     * @return void
     */
    public function handle()
    {
        $user = get_session('logged_user');

        if (empty($user)) {
            Redirect::toRoute('login.page')->only();
        }

        if ($user->role !== 'administrator') {
            Redirect::back()->only();
        }
    }
}
