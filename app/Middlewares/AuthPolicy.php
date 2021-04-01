<?php

namespace App\Middlewares;

use App\Helpers\Auth;
use Framework\Http\Request;
use Framework\Http\Redirect;
use App\Database\Models\RolesModel;

/**
 * Check if user is authenticated
 */
class AuthPolicy
{    
    /**
     * handle function
     *
     * @return void
     */
    public static function handle(Request $request): void
    {
        if (Auth::check()) {
            if (Auth::get()->role === RolesModel::ROLE[0]) {
                Redirect::url('admin/dashboard')->withToast(__('welcome') . ' ' . Auth::get()->name)->success();
            } else {
                Redirect::url('/');
            }
        }
    }
}
