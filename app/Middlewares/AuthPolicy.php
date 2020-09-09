<?php

namespace App\Middlewares;

use App\Helpers\AuthHelper;
use Framework\HTTP\Redirect;

/**
 * Check if user is authenticated
 */
class AuthPolicy
{    
    /**
     * handle function
     *
     * @return void
     */
    public static function handle(): void
    {
        if (AuthHelper::checkSession()) {
            if (AuthHelper::getSession()->role === 'admin') {
                Redirect::toUrl('/admin')->only();
            } else {
                Redirect::toUrl('/')->only();
            }
        }
    }
}
