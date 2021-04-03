<?php

namespace App\Controllers\Auth;

use Carbon\Carbon;
use App\Mails\WelcomeMail;
use Framework\Support\Session;
use App\Database\Models\Tokens;
use App\Database\Models\Users;
use Framework\Routing\Controller;
use App\Middlewares\DashboardPolicy;

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
        $user = Users::findSingleByEmail($this->request('email'));

		if (!$user) {
            $this->redirect('signup')->withAlert(__('user_not_registered', true))->error('');
        }

        $this->model('users')->updateBy(['email', $user->email], ['active' => 1]);
        WelcomeMail::send($user->email, $user->name);
        $this->redirect('login')->withAlert(__('user_activated', true))->success('');
    }
        
    /**
     * auth user from email link
     *
     * @return void
     */
    public function auth(): void
    {
        $auth_token = Tokens::findSingleByEmail($this->request('email'));

        if (!$auth_token || $auth_token->token !== $this->request('token')) {
			$this->response(__('invalid_two_steps_link', true));
		}

		if ($auth_token->expires < Carbon::now()->toDateTimeString()) {
			$this->response(__('expired_two_steps_link', true));
		}

        Tokens::deleteByEmail($auth_token->email);
        Session::create('user', Users::findSingleByEmail($auth_token->email));
        DashboardPolicy::handle($this->request);
    }
}
