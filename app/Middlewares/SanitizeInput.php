<?php

namespace App\Middlewares;

use Framework\Http\Request;

/**
 * SanitizeInput
 * 
 * Sanitize inputs
 */
class SanitizeInput
{
    /**
     * handle function
     * 
     * @return void
     */
    public function handle()
    {
        $request = new Request();
        $queries = $request->getInput();

        foreach ($queries as $key => $value) {
            $request->setInput($key, sanitize_string($value));
        }
    }
}
