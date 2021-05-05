<?php

namespace App\Http\Middlewares;

use Framework\Http\Request;
use Framework\Routing\View;

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
                View::render(config('errors.views.403'), [], 403);
            } else {
                response()->send(__('no_access_permission'), [], 403);
            }
        }
    }
}
