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
        $request = new Request();
        $queries = $request->getInput();

        foreach ($queries as $key => $value) {
            $request->setInput($key, sanitize_string($value));
        }
    }
}
