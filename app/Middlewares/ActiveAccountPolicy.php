<?php

namespace App\Middlewares;

use App\Helpers\AuthHelper;
use Framework\HTTP\Redirect;

/**
 * Check if user has admin role
 */
class ActiveAccountPolicy
{    
    /**
     * handle function
     *
     * @return void
     */
    public static function handle(): void
    {
        if (config('security.auth.email_confirmation') === true) {
            if (!AuthHelper::getSession()->active) {
                Redirect::toUrl('/login')->withError('Your account is not yet activated. Click the link below to confirm your email address. <br>
                    <a href="' . absolute_url('/email/confirmation/notify') . '"><u>Send me email confirmation link</u></a>');
            }
        }
    }
}
