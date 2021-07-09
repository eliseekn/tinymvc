<?php

namespace App\Http\Middlewares;

/**
 * Manage HTTP CORS
 */
class HttpCors
{    
    public function handle()
    {
        response()->headers([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => '*',
            'Access-Control-Allow-Methods' => '*',
        ]);
    }
}
