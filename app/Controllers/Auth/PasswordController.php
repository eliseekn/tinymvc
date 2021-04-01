<?php

namespace App\Controllers\Auth;

use Carbon\Carbon;
use App\Mails\TokenMail;
use App\Requests\AuthRequest;
use Framework\Routing\Controller;
use Framework\Support\Encryption;

/**
 * Manage password reset
 */
class PasswordController extends Controller
{
	/**
	 * send reset password link notification
	 *
	 * @return void
	 */
	public function notify(): void
	{
		$token = random_string(50, true);

		if (TokenMail::send($this->request->email, $token)) {
			$this->model('tokens')->insert([
				'email' => $this->request->email,
				'token' => $token,
				'expires' => Carbon::now()->addHour()->toDateTimeString()
            ]);
            
			$this->back()->withAlert(__('password_reset_link_sent', true))->success('');
		} 
        
		$this->back()->withAlert(__('password_reset_link_not_sent', true))->error('');
	}
	
	/**
	 * reset password
	 *
	 * @return void
	 */
	public function reset(): void
	{
        $reset_token = $this->model('tokens')->findSingleBy('email', $this->request->email);

        if ($reset_token === false || $reset_token->token !== $this->request->token) {
			$this->response(__('invalid_password_reset_link', true));
		}

		if ($reset_token->expires < Carbon::now()->toDateTimeString()) {
			$this->response(__('expired_password_reset_link', true));
		}

		$this->model('tokens')->deleteBy('email', $reset_token->email);
		$this->render('auth.password.new', ['email' => $reset_token->email]);
	}
	
	/**
	 * update user password
	 *
	 * @return void
	 */
	public function update(): void
	{
		AuthRequest::validate($this->request->inputs())->redirectOnFail();

        $this->model('users')->updateBy(['email', $this->request->email], ['password' => Encryption::hash($this->request->password)]);
        $this->redirect('login')->withAlert(__('password_resetted', true))->success('');
	}
}
