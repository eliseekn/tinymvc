<?php

namespace App\Controllers\Auth;

use App\Helpers\AuthHelper;
use Framework\HTTP\Request;
use App\Helpers\EmailHelper;
use Framework\HTTP\Redirect;
use App\Requests\AuthRequest;
use Framework\Support\Session;
use App\Middlewares\AuthPolicy;
use Framework\Support\Browsing;
use App\Requests\RegisterRequest;

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

        AuthHelper::authenticate();
        AuthPolicy::handle();
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
            Redirect::back()->withWarning('Please check your email account to confirm your email address.');
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
        Browsing::clearHistory();
        Session::close('csrf_token');
		Redirect::toUrl('/')->only();
	}
}
