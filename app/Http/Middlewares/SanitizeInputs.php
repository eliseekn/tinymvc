<?php

namespace App\Http\Middlewares;

use Core\Http\Request;

/**
 * Sanitize form fields
 */
class SanitizeInputs
{
    public function handle(Request $request)
    {
        foreach ($request->except('csrf_token') as $field => $value) {
            $request->set($field, sanitize($value));
        }
    }
}
