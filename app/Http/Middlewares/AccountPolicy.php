<?php

namespace App\Http\Middlewares;

use Framework\System\Auth;
use Framework\Http\Request;

/**
 * Check if email is verified
 */
class AccountPolicy
{    
    /**
     * handle function
     *
     * @param  \Framework\Http\Request $request
     * @return void
     */
    public function handle(Request $request): void
    {
        if (config('security.auth.email_verification') === true) {
            if (!Auth::get('email_verified')) {
                redirect()->url('login')->intended($request->fullUri())
                    ->withAlert('error', __('email_not_verifed'))->go();
            }
        }
    }
}
