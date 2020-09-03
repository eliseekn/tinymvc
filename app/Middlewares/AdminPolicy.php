<?php

namespace App\Middlewares;

use Framework\Routing\View;
use Framework\HTTP\Redirect;
use Framework\HTTP\Response;
use Framework\Support\Authenticate;

/**
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
            Redirect::toUrl('/login')->withError('You must be logged first to access this page');
        }
        
        if (Authenticate::getUser()->role !== 'admin') {
            if (isset(ERRORS_PAGE['403']) && !empty(ERRORS_PAGE['403'])) {
                View::render(ERRORS_PAGE['403'], [], 403);
            } else {
                Response::send([], 'You do not have permission to access this page', 403);
            }
        }
    }
}
