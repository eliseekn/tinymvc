<?php

namespace App\Middlewares;

use Framework\Http\Redirect;
use Framework\Support\Authenticate;

/**
 * Authentication
 * 
 * Check if user is connected
 */
class Authentication
{    
    /**
     * handle function
     *
     * @return void
     */
    public static function handle()
    {
        if (!Authenticate::check()) {
            Redirect::toRoute('login')->withError('You must be authenticated first.');
        }
    }
}
