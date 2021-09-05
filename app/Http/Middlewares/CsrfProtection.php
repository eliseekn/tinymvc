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
        if (config('app.env') === 'test') return;

        if (!$request->filled('csrf_token')) {
            throw new Exception('Missing csrf token');
        }

        if (!valid_csrf_token($request->csrf_token)) {
            throw new Exception('Invalid csrf token');
        }
    }
}
