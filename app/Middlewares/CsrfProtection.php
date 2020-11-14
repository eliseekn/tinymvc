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
        if (!valid_csrf_token(Request::getInput('csrf_token'))) {
            if (!empty(config('errors.views.403'))) {
                View::render(config('errors.views.403'), [], 403);
            } else {
                Response::send(__('no_access_permission', true), [], 403);
            }
        }
    }
}