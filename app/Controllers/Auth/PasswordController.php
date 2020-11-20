<?php

namespace App\Controllers\Auth;

use Carbon\Carbon;
use App\Helpers\EmailHelper;
use App\Requests\AuthRequest;
use Framework\Routing\Controller;
use Framework\Support\Encryption;
use App\Database\Models\UsersModel;
use App\Database\Models\TokensModel;

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

		if (EmailHelper::sendToken($this->request->email, $token)) {
			TokensModel::insert([
				'email' => $this->request->email,
				'token' => $token,
				'expires' => Carbon::now()->addHour()->toDateTimeString()
            ]);
            
			$this->redirectBack()->withSuccess(__('password_reset_link_sent', true));
		} else {
			$this->redirectBack()->withError(__('password_reset_link_not_sent', true));
		}
	}
	
	/**
	 * reset password
	 *
	 * @return void
	 */
	public function reset(): void
	{
        $reset_token = TokensModel::find('email', $this->request->email)->single();

        if ($reset_token === false || $reset_token->token !== $this->request->token) {
			$this->response(__('invalid_password_reset_link', true));
		}

		if ($reset_token->expires < Carbon::now()->toDateTimeString()) {
			$this->response(__('expired_password_reset_link', true));
		}

		TokensModel::delete()->where('email', $reset_token->email)->persist();
		
		$this->render('auth/password/new', [
			'email' => $reset_token->email
		]);
	}
	
	/**
	 * update user password
	 *
	 * @return void
	 */
	public function update(): void
	{
		$validate = AuthRequest::validate($this->request->inputs());
        
        if ($validate->fails()) {
            $this->redirectBack()->withError($validate->errors());
        }

        UsersModel::update(['password' => Encryption::hash($this->request->password)])
            ->where('email', $this->request->email)
            ->persist();
		
		$this->redirect('/login')->withSuccess(__('password_resetted', true));
	}
}
