<?php

namespace App\Middlewares;

use Framework\Http\Redirect;
use Framework\Support\Authenticate;

/**
 * AuthenticationPolicy
 * 
 * Check if user is authenticated
 */
class AuthenticationPolicy
{    
    /**
     * handle function
     *
     * @return void
     */
    public static function handle()
    {
        if (Authenticate::check()) {
            Redirect::toUrl('/admin')->only();
        }
    }
}
