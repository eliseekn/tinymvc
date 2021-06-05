<?php

namespace App\Http\Controllers\Auth;

use Core\System\Auth;
use Core\Http\Request;
use App\Mails\WelcomeMail;
use App\Database\Repositories\Users;
use App\Http\Validators\AuthRequest;
use App\Database\Repositories\Tokens;
use App\Http\Validators\RegisterUser;

/**
 * Manage user authentication
 */
class AuthController
{ 
    /**
     * display login page
     *
     * @return void
     */
    public function login(): void
    {
        if (!Auth::check()) {
            render('auth.login');
        }

        Auth::redirect();
    }

    /**
     * display signup page
     *
     * @return void
     */
    public function signup(): void
    {
        if (!Auth::check()) {
            render('auth.signup');
        }

        Auth::redirect();
    }

	/**
	 * authenticate user
	 * 
     * @param  \Core\Http\Request $request
     * @param  \App\Database\Repositories\Users $users
     * @param  \App\Database\Repositories\Tokens $tokens
	 * @return void
	 */
	public function authenticate(Request $request, Users $users, Tokens $tokens): void
	{
        AuthRequest::validate($request->except('csrf_token'))->redirectOnFail();
        Auth::attempt($request, $users, $tokens);
    }
    
    /**
     * register new user
     *
     * @param  \Core\Http\Request $request
     * @param  \App\Database\Repositories\Users $users
     * @param  \App\Database\Repositories\Tokens $tokens
     * @return void
     */
    public function register(Request $request, Users $users, Tokens $tokens): void
    {
        RegisterUser::register()->validate($request->except('csrf_token'))->redirectOnFail();
        Auth::create($request, $users);

        if (!config('security.auth.email_verification')) {
            WelcomeMail::send($request->email, $request->name);
            redirect()->url('login')->withAlert('success', __('account_created'))->go();
        }

        (new EmailVerificationController())->notify($request, $tokens);
    }
	
	/**
	 * logout
	 *
     * @param  string $redirect
	 * @return void
	 */
	public function logout(string $redirect = '/'): void
	{
		Auth::forget($redirect);
	}
}
