<?php

namespace App\Http\Middlewares;

use Core\Http\Request;

/**
 * Manage HTTP CORS
 */
class HttpCors
{    
    /**
     * handle function
     *
     * @param  \Core\Http\Request $request
     * @return void
     */
    public function handle(Request $request): void
    {
        response()->headers([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => '*',
            'Access-Control-Allow-Methods' => '*',
        ]);
    }
}
