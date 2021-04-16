<?php

namespace App\Controllers\Auth;

use Carbon\Carbon;
use App\Helpers\Auth;
use App\Mails\WelcomeMail;
use Framework\Http\Request;
use App\Validators\AuthRequest;
use App\Validators\RegisterUser;
use Framework\Routing\Controller;
use App\Database\Repositories\Users;
use App\Mails\EmailConfirmationMail;
use App\Database\Repositories\Tokens;

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

        if ($users->count()->single()->value === 1) {
            redirect()->route('dashboard.index')->go();
        }

        if (!config('auth.email_confirmation')) {
            WelcomeMail::send($request->email, $request->name);
            redirect()->route('dashboard.index')->withAlert('success', __('user_registered', true))->go();
        } else {
            $token = random_string(50, true);

            if (EmailConfirmationMail::send($request->email, $token)) {
                $tokens->store($request->email, $token, Carbon::now()->addDay()->toDateTimeString());
                redirect()->route('dashboard.index')->withAlert('success', __('confirm_email_link_sent', true))->go();
            } else {
                redirect()->route('dashboard.index')->withAlert('error', __('confirm_email_link_not_sent', true))->go();
            }
        }
    }
	
	/**
	 * logout
	 *
     * @param  string $redirect
	 * @return void
	 */
	public function logout(string $redirect = 'login'): void
	{
		Auth::forget();
        redirect()->url($redirect)->go();
	}
}
