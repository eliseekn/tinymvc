<?php

namespace App\Http\Controllers\Auth;

use Core\Support\Auth;
use Core\Http\Request;
use App\Mails\WelcomeMail;
use App\Database\Repositories\UserRepository;
use App\Http\Validators\AuthRequest;
use App\Database\Repositories\TokenRepository;
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

	public function authenticate(Request $request, UserRepository $userRepository, TokenRepository $tokenRepository)
	{
        AuthRequest::validate($request->except('csrf_token'))->redirectOnFail();
        Auth::attempt($request, $userRepository, $tokenRepository);
    }
    
    public function register(Request $request, UserRepository $userRepository, TokenRepository $tokenRepository)
    {
        RegisterUser::register()->validate($request->except('csrf_token'))->redirectOnFail();
        Auth::create($request, $userRepository);

        if (!config('security.auth.email_verification')) {
            WelcomeMail::send($request->email, $request->name);
            redirect()->url('login')->withAlert('success', __('account_created'))->go();
        }

        (new EmailVerificationController())->notify($request, $tokenRepository);
    }
	
	public function logout(string $redirect = '/')
	{
		Auth::forgetAndRedirect($redirect);
	}
}
