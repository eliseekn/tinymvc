<?php

namespace App\Middlewares;

use Framework\Routing\View;
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
    public static function handle(): void
    {
        if (!Authenticate::check()) {
            Redirect::toUrl('/login')->withError('You must be logged first.');
        }
        
        if (Authenticate::getUser()->role !== 'admin') {
            //send 403 response
            if (isset(ERRORS_PAGE['403']) && !empty(ERRORS_PAGE['403'])) {
                View::render(ERRORS_PAGE['403'], [], 403);
            } else {
                Response::send([], 'You do not have permission to access this page.', 404);
            }
        }
    }
}
