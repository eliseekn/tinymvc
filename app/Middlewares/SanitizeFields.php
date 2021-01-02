<?php

namespace App\Middlewares;

use Framework\Http\Request;

/**
 * Sanitize form fields
 */
class SanitizeFields
{
    /**
     * handle function
     * 
     * @return void
     */
    public static function handle(Request $request): void
    {
        foreach ($request->inputs() as $field => $value) {
            $request->input($field, escape($value));
        }
    }
}
