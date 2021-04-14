<?php

namespace App\Middlewares;

use App\Helpers\Auth;
use Framework\Routing\View;
use App\Database\Repositories\Roles;

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
    public function handle(): void
    {
        if (Auth::get('role') === Roles::ROLE[2]) {
            if (!empty(config('errors.views.403'))) {
                View::render(config('errors.views.403'), [], 403);
            }
                
            response()->send(__('no_access_permission', true), [], 403);
        }
    }
}
