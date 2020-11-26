<?php

namespace App\Middlewares;

use App\Helpers\AuthHelper;
use Framework\HTTP\Redirect;

/**
 * Check if user has admin role
 */
class AccountPolicy
{    
    /**
     * handle function
     *
     * @return void
     */
    public static function handle(): void
    {
        if (config('security.auth.email_confirmation') === true) {
            if (!AuthHelper::user()->active) {
                Redirect::toUrl('/login')->withAlert(__('account_not_activated', true))->error();
            }
        }
    }
}
