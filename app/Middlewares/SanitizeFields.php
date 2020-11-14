<?php

namespace App\Middlewares;

use Framework\HTTP\Request;

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
    public static function handle(): void
    {
        foreach (Request::getInputs() as $field => $value) {
            Request::setInput($field, escape($value));
        }
    }
}
