<?php

namespace App\Controllers\Auth;

use Carbon\Carbon;
use App\Helpers\Auth;
use App\Mails\WelcomeMail;
use Framework\Http\Request;
use Framework\System\Session;
use Framework\Routing\Controller;
use App\Database\Repositories\Roles;
use App\Database\Repositories\Users;
use App\Middlewares\DashboardPolicy;
use App\Database\Repositories\Tokens;

/**
 * Manage email confirmation
 */
class EmailController extends Controller
{
	/**
	 * confirm email confirmation link
	 *
     * @param  \Framework\Http\Request $request
     * @param  \App\Database\Repositories\Users $users
	 * @return void
	 */
	public function confirm(Request $request, Users $users): void
	{
        $user = $users->findSingleByEmail($request->email);

		if (!$user) {
            redirect()->url('signup')->withAlert('error', __('user_not_registered', true))->go();
        }

        $users->updateBy(['email', $user->email], ['active' => 1]);
        WelcomeMail::send($user->email, $user->name);
        redirect()->url('login')->withAlert('success', __('user_activated', true))->go();
    }
        
    /**
     * auth user from email link
     *
     * @param  \Framework\Http\Request $request
     * @param  \App\Database\Repositories\Users $users
     * @param  \App\Database\Repositories\Tokens $tokens
     * @return void
     */
    public function auth(Request $request, Users $users, Tokens $tokens): void
    {
        $auth_token = $tokens->findSingleByEmail($request->email);

        if (!$auth_token || $auth_token->token !== $request->token) {
			response()->send(__('invalid_two_steps_link', true));
		}

		if ($auth_token->expires < Carbon::now()->toDateTimeString()) {
			response()->send(__('expired_two_steps_link', true));
		}

        $tokens->deleteByEmail($auth_token->email);
        $user = $users->findSingleByEmail($request->email);

        Session::create('user', $user);
        Auth::redirect($user);
    }
}
