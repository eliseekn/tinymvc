<?php

namespace App\Http\Middlewares;

use App\Helpers\Auth;
use Framework\Http\Request;

/**
 * Check if user is authenticated
 */
class AuthPolicy
{    
    /**
     * handle function
     *
     * @param  \Framework\Http\Request $request
     * @return void
     */
    public function handle(Request $request): void
    {
        if (!Auth::check()) {
            redirect()->url('login')->intended($request->fullUri())
                ->withAlert('error', __('not_logged_error'))->go();
        }

    }
}
