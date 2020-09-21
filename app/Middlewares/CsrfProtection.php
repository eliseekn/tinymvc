<?php

namespace App\Middlewares;

use Framework\HTTP\Request;
use Framework\Routing\View;
use Framework\HTTP\Response;

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
    public static function handle(): void
    {
        if (!is_valid_csrf_token(Request::getField('csrf_token'))) {
            if (!empty(config('errors.views.403'))) {
                View::render(config('errors.views.403'), [], 403);
            } else {
                Response::send([], 'You do not have permission to access this page', 403);
            }
        }
    }
}