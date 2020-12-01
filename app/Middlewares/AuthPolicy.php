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
            if (!AuthHelper::hasRole('visitor')) {
                Redirect::toUrl('/admin/dashboard')->withToast(__('welcome') . ' ' . AuthHelper::user()->name)->success();
            } else {
                Redirect::toUrl('/')->withToast(__('welcome') . ' ' . AuthHelper::user()->name)->success();
            }
        }
    }
}
