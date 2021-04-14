<?php

namespace App\Controllers\Auth;

use Carbon\Carbon;
use App\Helpers\Auth;
use App\Mails\WelcomeMail;
use Framework\Http\Request;
use App\Requests\AuthRequest;
use App\Requests\RegisterUser;
use Framework\Routing\Controller;
use App\Database\Repositories\Users;
use App\Mails\EmailConfirmationMail;
use App\Database\Repositories\Tokens;

/**
 * Manage user authentication
 */
class AuthController extends Controller
{
    private $users;
    private $tokens;
    
    /**
     * __construct
     *
     * @param  \App\Database\Repositories\Users $users
     * @param  \App\Database\Repositories\Tokens $tokens
     * @return void
     */
    public function __construct(Users $users, Tokens $tokens)
    {
        $this->users = $users;
        $this->tokens = $tokens;
    }

	/**
	 * authenticate user
	 * 
     * @param  \Framework\Http\Request $request
	 * @return void
	 */
	public function authenticate(Request $request): void
	{
        AuthRequest::validate($request->inputs())->redirectOnFail();
        Auth::attempt($request, $this->users, $this->tokens);
    }
        
    /**
     * register new user
     *
     * @param  \Framework\Http\Request $request
     * @return void
     */
    public function register(Request $request): void
    {
        $validator = RegisterUser::validate($request->inputs())->redirectOnFail();
        
        if (!Auth::create($request, $this->users)) {
            redirect()->back()->withInputs($validator->inputs())
                ->withAlert('error', __('user_already_exists', true))->go();
        }

        if ($this->users->count()->single()->value === 1) {
            redirect()->route('dashboard.index')->go();
        }

        if (config('auth.email_confirmation') === false) {
            WelcomeMail::send($request->email, $request->name);
            redirect()->route('dashboard.index')->withAlert('success', __('user_registered', true))->go();
        } else {
            $token = random_string(50, true);

            if (EmailConfirmationMail::send($request->email, $token)) {
                $this->tokens->store($request->email, $token, Carbon::now()->addDay()->toDateTimeString());
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
