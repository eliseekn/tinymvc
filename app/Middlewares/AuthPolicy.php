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
            if (AuthHelper::hasRole('administrator')) {
                Redirect::toUrl('/admin/dashboard')->only();
            } else {
                Redirect::toUrl('/')->only();
            }
        }
    }
}
