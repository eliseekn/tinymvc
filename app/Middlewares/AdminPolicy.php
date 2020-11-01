<?php

namespace App\Middlewares;

use App\Helpers\AuthHelper;
use Framework\Routing\View;
use Framework\HTTP\Redirect;
use Framework\HTTP\Response;

/**
 * Check user permissions and role
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
            Redirect::toUrl('/login')->withError(__('not_logged_error', true));
        }
        
        if (!AuthHelper::hasRole('administrator')) {
            if (!empty(config('errors.views.403'))) {
                View::render(config('errors.views.403'), [], 403);
            } else {
                Response::send([], __('no_access_permission', true), 403);
            }
        }
    }
}
