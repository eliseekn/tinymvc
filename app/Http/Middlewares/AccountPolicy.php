<?php

namespace App\Http\Middlewares;

use Core\Support\Auth;
use Core\Http\Request;

/**
 * Check if email is verified
 */
class AccountPolicy
{    
    public function handle(Request $request)
    {
        if (config('security.auth.email_verification') === true) {
            if (!Auth::get('verified')) {
                redirect()->url('login')->intended($request->fullUri())
                    ->withAlert('error', __('email_not_verifed'))->go();
            }
        }
    }
}
