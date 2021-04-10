<?php

namespace App\Middlewares;

use App\Helpers\Auth;
use Framework\Http\Request;

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
        if (config('auth.email_confirmation') === true) {
            if (!Auth::get()->active) {
                redirect()->url('login')->withAlert(__('account_not_activated', true))->error('');
            }
        }
    }
}
