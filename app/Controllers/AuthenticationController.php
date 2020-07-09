<?php

namespace App\Controllers;

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
            Redirect::back()->withError('Invalid email address and/or password.');
        }
        
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
            Redirect::back()->withError('The email address provided is already used by another user.');
        }

        Email::new()
            ->to(Request::getField('email'))
            ->from(EMAIL['from'], EMAIL['name'])
            ->replyTo(EMAIL['from'], EMAIL['name'])
			->subject('Welcome')
            ->message('
                <p>Hello,</p> <br>
                <p>Congratulations, your account has been successfully created.</p>
            ')
			->asHTML()
			->send();

        Redirect::toUrl('/login')->withSuccess('You have been registered successfully. Log in now with your credentials.');
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
