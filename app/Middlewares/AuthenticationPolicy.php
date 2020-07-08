<?php

namespace App\Middlewares;

use Framework\HTTP\Redirect;
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
            if (Authenticate::getUser()->role === 'admin') {
                Redirect::toUrl('/admin')->only();
            } else {
                Redirect::toUrl('/')->only();
            }
        }
    }
}
