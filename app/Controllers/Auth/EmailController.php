<?php

namespace App\Controllers\Auth;

use Carbon\Carbon;
use App\Helpers\EmailHelper;
use Framework\Http\Redirect;
use Framework\Http\Response;
use Framework\Support\Session;
use App\Middlewares\AuthPolicy;
use Framework\Routing\Controller;
use App\Database\Models\UsersModel;
use App\Database\Models\TokensModel;

/**
 * Manage email confirmation
 */
class EmailController extends Controller
{
	/**
	 * confirm email confirmation link
	 *
	 * @return void
	 */
	public function confirm(): void
	{
		if (UsersModel::findBy('email', $this->request->email)->exists()) {
            UsersModel::update(['active' => 1])->where('email', $this->request->email)->persist();
            EmailHelper::sendWelcome($this->request->email);
            Redirect::url('login')->withAlert(__('user_activated', true))->success('');
        } else {
            Redirect::url('signup')->withAlert(__('user_not_registered', true))->error('');
        }
    }
        
    /**
     * auth user from email link
     *
     * @return void
     */
    public function auth(): void
    {
        $auth_token = TokensModel::findSingleBy('email', $this->request->email);

        if ($auth_token === false || $auth_token->token !== $this->request->token) {
			Response::send(__('invalid_two_steps_link', true));
		}

		if ($auth_token->expires < Carbon::now()->toDateTimeString()) {
			Response::send(__('expired_two_steps_link', true));
		}

        TokensModel::delete()->where('email', $auth_token->email)->persist();

        Session::create('user', UsersModel::findBy('email', $auth_token->email)->single());
        AuthPolicy::handle($this->request);
    }
}
