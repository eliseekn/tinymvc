<?php

namespace App\Http\Middlewares;

use App\Helpers\Auth;
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
        if (config('security.auth.email_confirmation') === true) {
            if (!Auth::get('status')) {
                redirect()->url('login')->intended($request->fullUri())
                    ->withAlert('error', __('account_not_activated'))->go();
            }
        }
    }
}
