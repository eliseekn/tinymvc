<?php

namespace App\Http\Middlewares;

use Core\Support\Auth;
use Core\Http\Request;
use Core\Support\Alert;

/**
 * Check if user is authenticated
 */
class AuthPolicy
{    
    public function handle(Request $request)
    {
        if (!Auth::check()) {
            Alert::default(__('not_logged'))->error();
            redirect()->url('login')->intended($request->fullUri())->go();
        }
    }
}
