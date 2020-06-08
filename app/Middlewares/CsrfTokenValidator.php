<?php

namespace App\Middlewares;

use Framework\Http\Request;
use Framework\Http\Response;

/**
 * CsrfTokenValidator
 * 
 * CSRF token validator
 */
class CsrfTokenValidator
{    
    /**
     * handle function
     *
     * @return void
     */
    public static function handle()
    {
        if (!is_valid_csrf_token(Request::getInput('csrf_token'))) {
            Response::send([], 'You do not have permission to access this page.', 403);
        }
    }
}