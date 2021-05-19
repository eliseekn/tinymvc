<?php

namespace App\Http\Middlewares;

use Core\System\Auth;
use Core\Http\Request;

/**
 * Check if email is verified
 */
class AccountPolicy
{    
    /**
     * handle function
     *
     * @param  \Core\Http\Request $request
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
