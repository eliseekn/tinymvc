<?php

namespace App\Middlewares;

use Framework\HTTP\Redirect;
use Framework\HTTP\Response;
use Framework\Support\Authenticate;

/**
 * AdminPolicy
 * 
 * Check if user has admin role
 */
class AdminPolicy
{    
    /**
     * handle function
     *
     * @return void
     */
    public static function handle()
    {
        if (!Authenticate::check()) {
            Redirect::toUrl('/login')->withError('You must be logged first.');
        }
        
        if (Authenticate::getUser()->role !== 'admin') {
            Response::send([], 'You do not have permission to access this page.', 403);
        }
    }
}
