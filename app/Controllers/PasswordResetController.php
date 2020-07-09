<?php

namespace App\Controllers;

use Framework\HTTP\Request;
use Framework\Routing\View;
use Framework\HTTP\Redirect;
use Framework\HTTP\Response;
use Framework\Support\Email;
use App\Validators\LoginForm;
use App\Database\Models\UsersModel;
use App\Database\Models\PasswordResetModel;

class PasswordResetController
{
	/**
	 * send reset password email notification
	 *
	 * @return void
	 */
	public function notify(): void
	{
		$token = random_string(20, true);
		$expires = strtotime('+1 hour', strtotime(date('Y-m-d H:i:s')));

		if (
			Email::new()
				->to(Request::getField('email'))
				->from(EMAIL['from'], EMAIL['name'])
            	->replyTo(EMAIL['from'], EMAIL['name'])
				->subject('Password reset notification')
				->message('
					<p>You are receiving this email because we received a password reset request for your account. Click the button below to reset your password:</p>
					<p><a href="' . absolute_url('/password/reset?token=' . $token) . '">' . absolute_url('/password/reset?token=' . $token) . '</a></p>
					<p>If you did not request a password reset, no further action is required.</p>
				')
				->asHTML()
				->send()
		) {
			PasswordResetModel::insert([
				'email' => Request::getField('email'),
				'token' => $token,
				'expires' => date('Y-m-d H:i:s', $expires)
			]);

			Redirect::back()->withSuccess('Your password reset link has been sumbitted successfuly. You can check your email box now.');
		} else {
			Redirect::back()->withError('Failed to send paswword reset link to your email address.');
		}
	}
	
	/**
	 * reset
	 *
	 * @return void
	 */
	public function reset(): void
	{
		if (PasswordResetModel::valid(Request::getQuery('email'), Request::getQuery('token')) === false) {
			Response::send([], 'This password reset link is invalid.');
		}

		if (PasswordResetModel::findWhere('email', Request::getQuery('email'))->expires < date('Y-m-d H:i:s')) {
			Response::send([], 'This password reset link expired. Please retrieves a new one.');
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
		LoginForm::validate([
			'redirect' => 'back'
		]);

		UsersModel::update(UsersModel::findWhere('email', Request::getField('email'))->id, [
			'password' => hash_string(Request::getField('password'))
		]);
		
        Redirect::back()->withSuccess('Your password has been resetted successfully.');
	}
}
