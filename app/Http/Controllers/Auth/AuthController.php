<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Helpers\Auth;
use App\Mails\WelcomeMail;
use Framework\Http\Request;
use Framework\Routing\Controller;
use App\Database\Repositories\Users;
use App\Http\Validators\AuthRequest;
use App\Mails\EmailConfirmationMail;
use App\Database\Repositories\Tokens;
use App\Http\Validators\RegisterUser;

/**
 * Manage user authentication
 */
class AuthController extends Controller
{
	/**
	 * authenticate user
	 * 
     * @param  \Framework\Http\Request $request
     * @param  \App\Database\Repositories\Users $users
     * @param  \App\Database\Repositories\Tokens $tokens
	 * @return void
	 */
	public function authenticate(Request $request, Users $users, Tokens $tokens): void
	{
        AuthRequest::validate($request->inputs())->redirectOnFail();
        Auth::attempt($request, $users, $tokens);
    }
        
    /**
     * register new user
     *
     * @param  \Framework\Http\Request $request
     * @param  \App\Database\Repositories\Users $users
     * @param  \App\Database\Repositories\Tokens $tokens
     * @return void
     */
    public function register(Request $request, Users $users, Tokens $tokens): void
    {
        RegisterUser::register()->validate($request->inputs())->redirectOnFail();
        Auth::create($request, $users);

        if (!config('security.auth.email_confirmation')) {
            WelcomeMail::send($request->email, $request->name);
            redirect()->url('login')->withAlert('success', __('user_registered', true))->go();
        }
        
        $token = random_string(50, true);

        if (EmailConfirmationMail::send($request->email, $token)) {
            $tokens->store($request->email, $token, Carbon::now()->addDay()->toDateTimeString());
            redirect()->url('login')->withAlert('success', __('confirm_email_link_sent', true))->go();
        }
            
        redirect()->back()->withAlert('error', __('confirm_email_link_not_sent', true))->go();
    }
	
	/**
	 * logout
	 *
     * @param  string $redirect
	 * @return void
	 */
	public function logout(string $redirect = '/'): void
	{
		Auth::forget();
        redirect()->url($redirect)->go();
	}
}
