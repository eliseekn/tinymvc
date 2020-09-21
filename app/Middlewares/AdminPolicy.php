<?php

namespace App\Middlewares;

use App\Helpers\AuthHelper;
use Framework\Routing\View;
use Framework\HTTP\Redirect;
use Framework\HTTP\Response;

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
        if (!AuthHelper::checkSession()) {
            Redirect::toUrl('/login')->withError('You must be logged first to access this page');
        }
        
        if (AuthHelper::getSession()->role !== 'admin') {
            if (!empty(config('errors.views.403'))) {
                View::render(config('errors.views.403'), [], 403);
            } else {
                Response::send([], 'You do not have permission to access this page', 403);
            }
        }
    }
}
