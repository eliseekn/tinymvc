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
     * @throws Exception
     */
    public function handle(Request $request)
    {
        if (!$request->filled('csrf_token')) {
            throw new Exception('Missing csrf token');
        }

        if (!valid_csrf_token($request->csrf_token)) {
            render(config('errors.views.403'), [], 403);
        }
    }
}
