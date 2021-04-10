<?php

namespace App\Middlewares;

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
     * @return void
     */
    public static function handle(Request $request): void
    {
        if ($request->exists('csrf_token') && !valid_csrf_token($request->csrf_token)) {
            if (!empty(config('errors.views.403'))) {
                View::render(config('errors.views.403'), [], 403);
            } else {
                response()->send(__('no_access_permission', true), [], 403);
            }
        }
    }
}
