<?php

namespace App\Controllers\Auth;

use Framework\HTTP\Request;
use App\Helpers\EmailHelper;
use Framework\HTTP\Redirect;
use App\Database\Models\UsersModel;

class EmailConfirmationController
{
	/**
	 * send email confirmation
	 *
	 * @return void
	 */
	public function send(): void
	{
		EmailHelper::sendConfirmation(Request::getField('email'), 'TinyMVC');
	}
	
	/**
	 * verify email confirmation link
	 *
	 * @return void
	 */
	public function verify(): void
	{
		if (UsersModel::exists('email', Request::getQuery('email'))) {
            UsersModel::updateWhere('email', Request::getQuery('email'), [
                'active' => 1
            ]);

            Redirect::toUrl('/')->withError('Your account has been successfully activated.');
        } else {
            Redirect::toUrl('/signup')->withError('Your account is not registred. Please register here.');
        }
	}
}
