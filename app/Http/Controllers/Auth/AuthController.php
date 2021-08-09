<?php

namespace App\Http\Controllers\Auth;

use Core\Http\Request;
use Core\Support\Auth;
use Core\Support\Alert;
use App\Mails\WelcomeMail;
use App\Http\Validators\AuthRequest;
use App\Http\Validators\RegisterUser;

/**
 * Manage user authentication
 */
class AuthController
{ 
    /**
     * Display login page
     */
    public function login()
    {
        if (!Auth::check()) {
            render('auth.login');
        }

        Auth::redirectIfLogged();
    }

    /**
     * Display signup page
     */
    public function signup()
    {
        if (!Auth::check()) {
            render('auth.signup');
        }

        Auth::redirectIfLogged();
    }

	public function authenticate(Request $request)
	{
        AuthRequest::validate($request->except('csrf_token'))->redirectOnFail();
        Auth::attempt($request);
    }
    
    public function register(Request $request)
    {
        RegisterUser::register()->validate($request->except('csrf_token'))->redirectOnFail();
        $user = Auth::create($request);

        if (!config('security.auth.email_verification')) {
            WelcomeMail::send($user->email, $user->name);

            Alert::default(__('account_created'))->success();
            redirect()->url('login')->go();
        }

        (new EmailVerificationController())->notify($request);
    }
	
	public function logout(string $redirect = '/')
	{
		Auth::forgetAndRedirect($redirect);
	}
}
