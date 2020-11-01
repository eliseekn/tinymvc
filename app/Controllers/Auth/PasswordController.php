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
use App\Database\Models\TokensModel;

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

		if (EmailHelper::sendToken(Request::getField('email'), $token)) {
			TokensModel::insert([
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
        $reset_token = TokensModel::find('email', Request::getQuery('email'))->single();

        if ($reset_token === false || $reset_token->token !== Request::getQuery('token')) {
			Response::send([], 'This password reset link is invalid');
		}

		if ($reset_token->expires < date('Y-m-d H:i:s')) {
			Response::send([], 'This password reset link expired. Please retrieves a new one');
		}

		TokensModel::delete()->where('email', $reset_token->email)->persist();
		
		View::render('password/new', [
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
            Redirect::back()->withError($validate);
        }

        UsersModel::update(['password' => Encryption::hash(Request::getField('password'))])
            ->where('email', Request::getField('email'))
            ->persist();
		
		Redirect::toUrl('/login')->withSuccess('Your password has been resetted successfully');
	}
}
