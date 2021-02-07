<?php

namespace App\Middlewares;

use App\Helpers\Auth;
use Framework\Http\Request;
use Framework\Routing\View;
use Framework\Http\Redirect;
use Framework\Http\Response;
use App\Database\Models\RolesModel;

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
            Redirect::url('login')->withAlert(__('not_logged_error', true))->error('');
        }

        if (Auth::role(RolesModel::ROLE[2])) {
            if (!empty(config('errors.views.403'))) {
                View::render(config('errors.views.403'), [], 403);
            } else {
                Response::send(__('no_access_permission', true), false, [], 403);
            }
        }
    }
}
