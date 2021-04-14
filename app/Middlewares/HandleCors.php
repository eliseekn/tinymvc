<?php

namespace App\Middlewares;

class HandleCors
{    
    /**
     * handle function
     *
     * @return void
     */
    public function handle(): void
    {
        response()->headers([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => '*',
            'Access-Control-Allow-Methods' => '*',
        ]);
    }
}
