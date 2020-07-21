<?php

namespace App\Middlewares;

use Framework\HTTP\Request;
use Framework\Routing\View;
use Framework\HTTP\Response;

/**
 * CsrfProtection
 * 
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
            //send 403 response
            if (isset(ERRORS_PAGE['403']) && !empty(ERRORS_PAGE['403'])) {
                View::render(ERRORS_PAGE['403'], [], 403);
            } else {
                Response::send([], 'You do not have permission to access this page.');
            }
        }
    }
}