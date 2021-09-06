<?php

namespace App\Http\Middlewares;

use Core\Support\Auth;
use Core\Http\Request;
use Core\Support\Alert;

/**
 * Check if user has been authenticated
 */
class AuthPolicy
{    
    public function handle(Request $request)
    {
        if (!Auth::check($request)) {
            Alert::default(__('not_logged'))->error();
            redirect()->to('login')->intended($request->fullUri())->withErrors([__('not_logged')])->go();
        }
    }
}
