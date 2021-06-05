<?php

namespace App\Http\Middlewares;

use Core\System\Auth;
use Core\Http\Request;

/**
 * Check if user is authenticated
 */
class AuthPolicy
{    
    /**
     * handle function
     *
     * @param  \Core\Http\Request $request
     * @return void
     */
    public function handle(Request $request): void
    {
        if (!Auth::check()) {
            redirect()->url('login')->intended($request->fullUri())
                ->withAlert('error', __('not_logged'))->go();
        }
    }
}
