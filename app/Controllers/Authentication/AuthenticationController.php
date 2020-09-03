<?php

namespace App\Controllers\Authentication;

use Framework\HTTP\Request;
use Framework\HTTP\Redirect;
use Framework\Support\Email;
use App\Validators\LoginForm;
use App\Validators\RegisterForm;
use Framework\Support\Authenticate;

/**
 * AuthenticationController
 * 
 * Manage user authentication
 */
class AuthenticationController
{
	/**
	 * authenticate user
	 * 
	 * @return void
	 */
	public function authenticate(): void
	{
		LoginForm::validate([
			'redirect' => 'back'
        ]);

		if (!Authenticate::attempt()) {
            if (AUTH_ATTEMPTS !== 0 && Authenticate::getAttempts() > AUTH_ATTEMPTS) {
                create_session('auth_attempts_timeout', strtotime('+' . AUTH_ATTEMPTS_TIMEOUT . ' minute', strtotime(date('Y-m-d H:i:s'))));
                Redirect::back()->withError('Authentication attempts exceeded. <br> Wait ' . AUTH_ATTEMPTS_TIMEOUT . ' minute(s) and refresh the page to try again');
            } else {
                Redirect::back()->withError('Invalid email address and/or password');
            }
        }

        //disable authentication lock
        close_session('auth_attempts_timeout');
        
        if (Authenticate::getUser()->role === 'admin') {
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
        RegisterForm::validate([
            'redirect' => 'back'
        ]);

        Request::setField('password', hash_string(Request::getField('password')));

        if (!Authenticate::new(['name', 'email', 'password'])) {
            Redirect::back()->withError('The email address provided is already used by another user');
        }

        Email::to(Request::getField('email'))
            ->from(EMAIL['from'], EMAIL['name'])
            ->replyTo(EMAIL['from'], EMAIL['name'])
			->subject('Welcome')
            ->message('
                <p>Hello,</p>
                <p>Congratulations, your account has been successfully created.</p>
            ')
			->asHTML()
			->send();

        Redirect::toUrl('/login')->withSuccess('You have been registered successfully. Log in now with your credentials');
    }
	
	/**
	 * logout
	 *
	 * @return void
	 */
	public function logout(): void
	{
		Authenticate::logout();
		Redirect::toUrl('/')->only();
	}
}
