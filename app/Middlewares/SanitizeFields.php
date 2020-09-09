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
        foreach (Request::getFields() as $field => $value) {
            Request::setField($field, escape($value));
        }
    }
}
