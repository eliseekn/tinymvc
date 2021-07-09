<?php

namespace App\Http\Middlewares;

use Core\Support\Auth;
use Core\Http\Request;

/**
 * Check if user is authenticated
 */
class AuthPolicy
{    
    public function handle(Request $request)
    {
        if (!Auth::check()) {
            redirect()->url('login')->intended($request->fullUri())
                ->withAlert('error', __('not_logged'))->go();
        }
    }
}
