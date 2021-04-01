<?php

namespace App\Controllers\Auth;

use Carbon\Carbon;
use App\Mails\WelcomeMail;
use Framework\Support\Session;
use App\Middlewares\AuthPolicy;
use Framework\Routing\Controller;

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
        $user = $this->model('users')->findSingleBy('email', $this->request->email);

		if ($user) {
            $this->model('users')->updateBy(['email', $user->email], ['active' => 1]);
            WelcomeMail::send($user->email, $user->name);
            
            $this->redirect('login')->withAlert(__('user_activated', true))->success('');
        } else {
            $this->redirect('signup')->withAlert(__('user_not_registered', true))->error('');
        }
    }
        
    /**
     * auth user from email link
     *
     * @return void
     */
    public function auth(): void
    {
        $auth_token = $this->model('tokens')->findSingleBy('email', $this->request->email);

        if ($auth_token === false || $auth_token->token !== $this->request->token) {
			$this->response(__('invalid_two_steps_link', true));
		}

		if ($auth_token->expires < Carbon::now()->toDateTimeString()) {
			$this->response(__('expired_two_steps_link', true));
		}

        $this->model('tokens')->deleteBy('email', $auth_token->email);

        Session::create('user', $this->model('users')->findSingleBy('email', $auth_token->email));
        AuthPolicy::handle($this->request);
    }
}
