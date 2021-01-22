<?php

namespace App\Middlewares;

use Framework\Http\Request;
use Framework\Routing\View;
use Framework\Http\Response;

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
        if (!valid_csrf_token($request->csrf_token)) {
            if (!empty(config('errors.views.403'))) {
                View::render(config('errors.views.403'), [], 403);
            } else {
                Response::send(__('no_access_permission', true), false, [], 403);
            }
        }
    }
}