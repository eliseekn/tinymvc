<?php

namespace App\Http\Middlewares;

use Exception;
use Core\Http\Request;

/**
 * CSRF token validator
 */
class CsrfProtection
{    
    /**
     * handle function
     *
     * @param  \Core\Http\Request $request
     * @return void
     * 
     * @throws Exception
     */
    public function handle(Request $request): void
    {
        if (!$request->filled('csrf_token')) {
            throw new Exception('Missing csrf token input');
        }

        if (!valid_csrf_token($request->csrf_token)) {
            render(config('errors.views.403'), [], 403);
        }
    }
}
