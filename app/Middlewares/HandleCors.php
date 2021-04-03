<?php

namespace App\Middlewares;

use Framework\Http\Request;
use Framework\Http\Response;

class HandleCors
{    
    /**
     * handle function
     *
     * @return void
     */
    public static function handle(Request $request): void
    {
        (new Response())->headers([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => '*',
            'Access-Control-Allow-Methods' => '*',
        ]);
    }
}
