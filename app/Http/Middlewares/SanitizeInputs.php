<?php

namespace App\Http\Middlewares;

use Framework\Http\Request;

/**
 * Sanitize form fields
 */
class SanitizeInputs
{
    /**
     * handle function
     * 
     * @return void
     */
    public function handle(Request $request): void
    {
        foreach ($request->inputs() as $field => $value) {
            $request->set($field, escape($value));
        }
    }
}
