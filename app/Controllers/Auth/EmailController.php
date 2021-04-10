<?php

namespace App\Controllers\Auth;

use Carbon\Carbon;
use App\Mails\WelcomeMail;
use Framework\Support\Session;
use App\Database\Models\Tokens;
use App\Database\Models\Users;
use Framework\Routing\Controller;
use App\Middlewares\DashboardPolicy;
use Framework\Http\Request;

/**
 * Manage email confirmation
 */
class EmailController extends Controller
{
	/**
	 * confirm email confirmation link
	 *
     * @param  \Framework\Http\Request $request
	 * @return void
	 */
	public function confirm(Request $request): void
	{
        $user = Users::findSingleByEmail($request->email);

		if (!$user) {
            redirect()->url('signup')->withAlert(__('user_not_registered', true))->error('');
        }

        $this->model('users')->updateBy(['email', $user->email], ['active' => 1]);
        WelcomeMail::send($user->email, $user->name);
        redirect()->url('login')->withAlert(__('user_activated', true))->success('');
    }
        
    /**
     * auth user from email link
     *
     * @param  \Framework\Http\Request $request
     * @return void
     */
    public function auth(Request $request): void
    {
        $auth_token = Tokens::findSingleByEmail($request->email);

        if (!$auth_token || $auth_token->token !== $request->token) {
			response()->send(__('invalid_two_steps_link', true));
		}

		if ($auth_token->expires < Carbon::now()->toDateTimeString()) {
			response()->send(__('expired_two_steps_link', true));
		}

        Tokens::deleteByEmail($auth_token->email);
        Session::create('user', Users::findSingleByEmail($auth_token->email));
        DashboardPolicy::handle($request);
    }
}
