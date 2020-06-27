<?php

namespace App\Middlewares;

use Framework\Http\Response;
use Framework\Support\Authenticate;

/**
 * AdminSession
 * 
 * Check if user has admin role
 */
class AdminSession
{    
    /**
     * handle function
     *
     * @return void
     */
    public static function handle()
    {
        if (Authenticate::getUser()->role !== 'admin') {
            Response::send([], 'You do not have permission to access this page.', 403);
        }
    }
}
