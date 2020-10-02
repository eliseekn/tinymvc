<?php

namespace App\Controllers\Auth;

use App\Helpers\AuthHelper;
use App\Helpers\EmailHelper;
use Framework\HTTP\Request;
use Framework\HTTP\Redirect;
use Framework\Support\Email;
use App\Requests\AuthRequest;
use App\Requests\RegisterRequest;
use Framework\Support\Validator;

/**
 * Manage user authentication
 */
class AuthController
{
	/**
	 * authenticate user
	 * 
	 * @return void
	 */
	public function authenticate(): void
	{
        $validate = AuthRequest::validate(Request::getFields());
        
        if (is_array($validate)) {
            Redirect::back()->withError($validate);
        }

        if (AuthHelper::authenticate()->role === 'admin') {
            Redirect::toUrl('/admin')->only();
        } else {
            Redirect::toUrl('/')->only();
        }
    }
        
    /**
     * register new user
     *
     * @return void
     */
    public function register(): void
    {
        $validate = RegisterRequest::validate(Request::getFields());
        
        if (is_array($validate)) {
            Redirect::back()->withError($validate);
        }

        if (!AuthHelper::store()) {
            Redirect::back()->withError('The email address is already used by another user');
        }

        if (config('security.auth.email_confirmation') === true) {
            EmailHelper::sendWelcome(Request::getField('email'));
            Redirect::toUrl('/login')->withSuccess('You have been registered successfully. <br> You can log in with your credentials');
        } else {
            EmailHelper::sendConfirmation(Request::getField('email'), 'TinyMVC');
            Redirect::back()->withInfo('Please check your email account to confirm your email address.');
        }
    }
	
	/**
	 * logout
	 *
	 * @return void
	 */
	public function logout(): void
	{
		AuthHelper::forget();
		Redirect::toUrl('/')->only();
	}
}
