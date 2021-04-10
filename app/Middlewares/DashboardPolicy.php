<?php

namespace App\Middlewares;

use App\Helpers\Auth;
use Framework\Http\Request;
use Framework\Routing\View;
use App\Database\Models\Roles;

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
    public static function handle(Request $request): void
    {
        if (!Auth::check()) {
            redirect()->url('login')->withAlert(__('not_logged_error', true))->error('');
        }

        if (Auth::get('role') === Roles::ROLE[2]) {
            if (!empty(config('errors.views.403'))) {
                View::render(config('errors.views.403'), [], 403);
            } else {
                response()->send(__('no_access_permission', true), [], 403);
            }
        }
    }
}
