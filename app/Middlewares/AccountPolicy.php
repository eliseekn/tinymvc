<?php

namespace App\Middlewares;

use App\Helpers\Auth;
use Framework\Http\Request;
use Framework\Http\Redirect;

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
    public static function handle(Request $request): void
    {
        if (config('security.auth.email_confirmation') === true) {
            if (!Auth::get()->active) {
                (new Redirect())->url('login')->withAlert(__('account_not_activated', true))->error('');
            }
        }
    }
}
