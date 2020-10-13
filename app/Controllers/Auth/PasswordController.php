<?php

namespace App\Controllers\Auth;

use Framework\HTTP\Request;
use Framework\Routing\View;
use App\Helpers\EmailHelper;
use Framework\HTTP\Redirect;
use Framework\HTTP\Response;
use App\Requests\AuthRequest;
use Framework\Support\Encryption;
use App\Database\Models\UsersModel;
use App\Database\Models\PasswordResetModel;

/**
 * Manage password reset
 */
class PasswordController
{
	/**
	 * send reset password email notification
	 *
	 * @return void
	 */
	public function notify(): void
	{
		$token = random_string(50, true);
		$expires = strtotime('+1 hour', strtotime(date('Y-m-d H:i:s')));

		if (EmailHelper::sendToken(Request::getField('email'), $token)) {
			PasswordResetModel::create([
				'email' => Request::getField('email'),
				'token' => $token,
				'expires' => date('Y-m-d H:i:s', $expires)
			]);

			Redirect::back()->withSuccess('Your password reset link has been sumbitted successfuly. <br> You can check your email box now');
		} else {
			Redirect::back()->withError('Failed to send paswword reset link to your email address');
		}
	}
	
	/**
	 * reset password
	 *
	 * @return void
	 */
	public function reset(): void
	{
		if (PasswordResetModel::findWhere([
			'email' => Request::getQuery('email'), 
			'token' => Request::getQuery('token')
		]) === false) {
			Response::send([], 'This password reset link is invalid');
		}

		if (PasswordResetModel::findWhere(['email', Request::getQuery('email')])->expires < date('Y-m-d H:i:s')) {
			Response::send([], 'This password reset link expired. Please retrieves a new one');
		}

		PasswordResetModel::deleteWhere('email', Request::getQuery('email'));
		
		View::render('password/new', [
			'email' => Request::getQuery('email')
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
            Redirect::back()->withError($validate);
        }

		UsersModel::updateWhere('email', Request::getField('email'), [
			'password' => Encryption::hash(Request::getField('password'))
		]);
		
		Redirect::toUrl('/login')->withSuccess('Your password has been resetted successfully');
	}
}
