<?php

namespace App\Http\Middlewares;

use Framework\Http\Request;

/**
 * CSRF token validator
 */
class CsrfProtection
{    
    /**
     * handle function
     *
     * @param  \Framework\Http\Request $request
     * @return void
     */
    public function handle(Request $request): void
    {
        if ($request->filled('csrf_token') && !valid_csrf_token($request->csrf_token)) {
            if (!empty(config('errors.views.403'))) {
                render(config('errors.views.403'), [], 403);
            }
                
            response()->send(__('access_denied'), [], 403);
        }
    }
}
