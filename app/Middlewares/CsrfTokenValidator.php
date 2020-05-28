<?php

namespace App\Middlewares;

use Framework\Http\Request;
use Framework\Http\Redirect;

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
    public function handle()
    {
        $request = new Request();
        $csrf_token = $request->getInput('csrf_token');

        if (!is_valid_csrf_token($csrf_token)) {
            Redirect::back()->withMessage('csrf_token_error', 'Invalid csrf token.');
        }
    }
}