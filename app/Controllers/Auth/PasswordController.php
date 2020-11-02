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
	 * send reset password email notification
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

			$this->redirect()->withSuccess('Your password reset link has been sumbitted successfuly. <br> You can check your email box now');
		} else {
			$this->redirect()->withError('Failed to send paswword reset link to your email address');
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
			$this->response('This password reset link is invalid');
		}

		if ($reset_token->expires < Carbon::now()->toDateTimeString()) {
			$this->response('This password reset link expired. Please retrieves a new one');
		}

		TokensModel::delete()->where('email', $reset_token->email)->persist();
		
		$this->render('password/new', [
			'email' => $reset_token->email
		]);
	}
	
	/**
	 * set new user password
	 *
	 * @return void
	 */
	public function new(): void
	{
		$validate = AuthRequest::validate(Request::getFields());
        
        if (is_array($validate)) {
            $this->redirect()->withError($validate);
        }

        UsersModel::update(['password' => Encryption::hash(Request::getField('password'))])
            ->where('email', Request::getField('email'))
            ->persist();
		
		$this->redirect('/login')->withSuccess('Your password has been resetted successfully');
	}
}
