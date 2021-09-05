<?php

namespace App\Http\Middlewares;

use Core\Http\Request;
use Core\Support\Auth;
use Core\Support\Alert;

/**
 * Check if email has been verified
 */
class AccountPolicy
{    
    public function handle(Request $request)
    {
        if (config('security.auth.email_verification') === true) {
            if (!Auth::get('verified')) {
                Alert::default(__('email_not_verifed'))->error();
                redirect()->to('login')->intended($request->fullUri())->go();
            }
        }
    }
}
