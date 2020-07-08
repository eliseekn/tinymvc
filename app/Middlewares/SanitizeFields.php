<?php

namespace App\Middlewares;

use Framework\HTTP\Request;

/**
 * SanitizeFields
 * 
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
        foreach (Request::getField() as $field => $value) {
            Request::setField($field, escape($value));
        }
    }
}
