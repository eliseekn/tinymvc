<?php

namespace App\Middlewares;

use Framework\Http\Request;

/**
 * SanitizeInputs
 * 
 * Sanitize post request inputs
 */
class SanitizeInputs
{
    /**
     * handle function
     * 
     * @return void
     */
    public function handle(): void
    {
        foreach (Request::getInput() as $key => $value) {
            Request::setInput($key, sanitize_string($value));
        }
    }
}
