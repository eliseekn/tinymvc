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
    public function login(Request $request)
    {
        if (!Auth::check($request)) {
            render('auth.login');
        }

        Auth::redirectIfLogged($request, );
    }

    public function signup(Request $request)
    {
        if (!Auth::check($request)) {
            render('auth.signup');
        }

        Auth::redirectIfLogged($request, );
    }

	public function authenticate(Request $request)
	{
        AuthRequest::validate($request->except('csrf_token'))->redirectBackOnFail();
        Auth::attempt($request);
    }
    
    public function register(Request $request)
    {
        RegisterUser::register()->validate($request->except('csrf_token'))->redirectBackOnFail();
        $user = Auth::create($request);

        if (!config('security.auth.email_verification')) {
            WelcomeMail::send($user->email, $user->name);

            Alert::default(__('account_created'))->success();
            redirect()->to('login')->go();
        }

        (new EmailVerificationController())->notify($request);
    }
	
	public function logout(Request $request, string $redirect = '/')
	{
		Auth::forgetAndRedirect($request, $redirect);
	}
}
