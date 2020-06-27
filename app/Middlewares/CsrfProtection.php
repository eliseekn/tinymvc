<?php

namespace App\Middlewares;

use Framework\Http\Request;
use Framework\Http\Response;

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
    public static function handle()
    {
        if (!is_valid_csrf_token(Request::getField('csrf_token'))) {
            Response::send([], 'You do not have permission to access this page.', 403);
        }
    }
}