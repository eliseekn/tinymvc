<?php

namespace App\Controllers\Auth;

use Carbon\Carbon;
use App\Helpers\AuthHelper;
use App\Helpers\EmailHelper;
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
		if (UsersModel::find('email', $this->request->email)->exists()) {
            UsersModel::update(['active' => 1])->where('email', $this->request->email)->persist();
            EmailHelper::sendWelcome($this->request->email);
            $this->redirect('/login')->withSuccess(__('user_activated', true));
        } else {
            $this->redirect('/signup')->withError(__('user_not_registered', true));
        }
    }
        
    /**
     * auth user from email link
     *
     * @return void
     */
    public function auth(): void
    {
        $auth_token = TokensModel::find('email', $this->request->email)->single();

        if ($auth_token === false || $auth_token->token !== $this->request->token) {
			$this->response(__('invalid_two_factor_link', true));
		}

		if ($auth_token->expires < Carbon::now()->toDateTimeString()) {
			$this->response(__('expired_two_factor_link', true));
		}

        TokensModel::delete()->where('email', $auth_token->email)->persist();

        AuthHelper::authEmail($auth_token->email);
        AuthPolicy::handle();
    }
}
