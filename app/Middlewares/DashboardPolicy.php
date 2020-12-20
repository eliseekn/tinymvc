<?php

namespace App\Middlewares;

use App\Helpers\Auth;
use Framework\Routing\View;
use Framework\HTTP\Redirect;
use Framework\HTTP\Response;

/**
 * Check user role
 */
class DashboardPolicy
{    
    /**
     * handle function
     *
     * @return void
     */
    public static function handle(): void
    {
        if (!Auth::check()) {
            Redirect::url('login')->withAlert(__('not_logged_error', true))->error('');
        }
        
        if (Auth::hasRole('visitor')) {
            if (!empty(config('errors.views.403'))) {
                View::render(config('errors.views.403'), [], 403);
            } else {
                Response::send(__('no_access_permission', true), [], 403);
            }
        }
    }
}
