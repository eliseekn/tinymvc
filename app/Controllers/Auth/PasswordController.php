<?php

namespace App\Controllers\Auth;

use Carbon\Carbon;
use Framework\HTTP\Request;
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

		if (EmailHelper::sendToken(Request::getField('email'), $token)) {
			TokensModel::insert([
				'email' => Request::getField('email'),
				'token' => $token,
				'expires' => Carbon::now()->addHour()->toDateTimeString()
            ]);
            
			$this->redirect()->withSuccess(__('password_reset_link_sent', true));
		} else {
			$this->redirect()->withError(__('password_reset_link_not_sent', true));
		}
	}
	
	/**
	 * reset password
	 *
	 * @return void
	 */
	public function reset(): void
	{
        $reset_token = TokensModel::find('email', Request::getQuery('email'))->single();

        if ($reset_token === false || $reset_token->token !== Request::getQuery('token')) {
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
		$validate = AuthRequest::validate(Request::getFields());
        
        if ($validate->fails()) {
            $this->redirect()->withError($validate::$errors);
        }

        UsersModel::update(['password' => Encryption::hash(Request::getField('password'))])
            ->where('email', Request::getField('email'))
            ->persist();
		
		$this->redirect('/login')->withSuccess(__('password_resetted', true));
	}
}
