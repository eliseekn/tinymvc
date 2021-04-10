<?php

namespace App\Controllers\Auth;

use Carbon\Carbon;
use App\Helpers\Auth;
use App\Mails\WelcomeMail;
use Framework\Http\Request;
use App\Requests\AuthRequest;
use App\Requests\RegisterUser;
use App\Database\Models\Tokens;
use Framework\Routing\Controller;
use App\Mails\EmailConfirmationMail;

/**
 * Manage user authentication
 */
class AuthController extends Controller
{
	/**
	 * authenticate user
	 * 
     * @param  \Framework\Http\Request $request
	 * @return void
	 */
	public function authenticate(Request $request): void
	{
        AuthRequest::validate($request->inputs())->redirectOnFail();
        Auth::attempt($request);
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
        
        if (!Auth::create($request)) {
            redirect()->back()->withInputs($validator->inputs())
                ->withAlert(__('user_already_exists', true))->error('');
        }

        if ($this->model('users')->count()->single()->value === 1) {
            redirect()->route('dashboard.index')->only();
        }

        if (config('auth.email_confirmation') === false) {
            WelcomeMail::send($request->email, $request->name);
            redirect()->route('dashboard.index')->withAlert(__('user_registered', true))->success('');
        } else {
            $token = random_string(50, true);

            if (EmailConfirmationMail::send($request->email, $token)) {
                Tokens::store($request->email, $token, Carbon::now()->addDay()->toDateTimeString());
                redirect()->route('dashboard.index')->withAlert(__('confirm_email_link_sent', true))->success('');
            } else {
                redirect()->route('dashboard.index')->withAlert(__('confirm_email_link_not_sent', true))->error('');
            }
        }
    }
	
	/**
	 * logout
	 *
     * @param  string|null $redirect
	 * @return void
	 */
	public function logout(?string $redirect = null): void
	{
		Auth::forget();

        $redirect = is_null($redirect) ? 'login' : $redirect;
		redirect()->url($redirect)->only();
	}
}
