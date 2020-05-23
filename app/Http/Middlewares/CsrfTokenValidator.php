<?php

namespace App\Http\Middlewares;

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
        $csrf_token = $request->postQuery('csrf_token');

        if (!is_valid_csrf_token($csrf_token)) {
            Redirect::toRoute('login.page')->only();
        }
    }
}