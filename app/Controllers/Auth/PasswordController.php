<?php

namespace App\Controllers\Auth;

use Carbon\Carbon;
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
		//$expires = strtotime('+1 hour', strtotime(date('Y-m-d H:i:s')));

		if (EmailHelper::sendToken(Request::getField('email'), $token)) {
			PasswordResetModel::insert([
				'email' => Request::getField('email'),
				'token' => $token,
				'expires' => Carbon::now()->addHour()->format('Y-m-d H:i:s')
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
        $pasword_reset = PasswordResetModel::find('email', Request::getQuery('email'))->single();

        if ($pasword_reset === false || $pasword_reset->token !== Request::getQuery('token')) {
			Response::send([], 'This password reset link is invalid');
		}

		if ($pasword_reset->expires < date('Y-m-d H:i:s')) {
			Response::send([], 'This password reset link expired. Please retrieves a new one');
		}

		PasswordResetModel::delete()->where('email', $pasword_reset->email)->persist();
		
		View::render('password/new', [
			'email' => $pasword_reset->email
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

        UsersModel::update(['password' => Encryption::hash(Request::getField('password'))])
            ->where('email', Request::getField('email'))
            ->persist();
		
		Redirect::toUrl('/login')->withSuccess('Your password has been resetted successfully');
	}
}
