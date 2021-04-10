<?php

namespace App\Middlewares;

use Framework\Http\Request;

class HandleCors
{    
    /**
     * handle function
     *
     * @return void
     */
    public static function handle(Request $request): void
    {
        response()->headers([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => '*',
            'Access-Control-Allow-Methods' => '*',
        ]);
    }
}
