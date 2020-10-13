<?php

namespace App\Controllers\Auth;

use Framework\HTTP\Request;
use App\Helpers\EmailHelper;
use Framework\HTTP\Redirect;
use App\Database\Models\UsersModel;

/**
 * Manage email confirmation
 */
class EmailController
{
	/**
	 * send email confirmation
	 *
	 * @return void
	 */
	public function notify(): void
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
		if (UsersModel::has(['email' => Request::getQuery('email')])) {
            UsersModel::update(['active' => 1])->where('email', '=', Request::getQuery('email'));

            Redirect::toUrl('/')->withError('Your account has been successfully activated.');
        } else {
            Redirect::toUrl('/signup')->withError('Your account is not registred. Please register here.');
        }
	}
}
