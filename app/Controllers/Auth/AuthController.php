<?php

namespace App\Controllers\Auth;

use Carbon\Carbon;
use App\Helpers\Auth;
use App\Helpers\EmailHelper;
use Framework\HTTP\Redirect;
use App\Requests\AuthRequest;
use App\Requests\RegisterUser;
use Framework\Routing\Controller;
use App\Database\Models\TokensModel;

/**
 * Manage user authentication
 */
class AuthController extends Controller
{
	/**
	 * authenticate user
	 * 
	 * @return void
	 */
	public function authenticate(): void
	{
        $validator = AuthRequest::validate($this->request->inputs());

        if ($validator->fails()) {
            Redirect::back()->withErrors($validator->errors())->withInputs($validator->inputs())
                ->withAlert(__('login_failed', true))->error('');
        }

        Auth::attempt($this->request);
    }
        
    /**
     * register new user
     *
     * @return void
     */
    public function register(): void
    {
        $validator = RegisterUser::validate($this->request->inputs());
        
        if ($validator->fails()) {
            Redirect::back()->withErrors($validator->errors())->withInputs($validator->inputs())
                ->withAlert(__('signup_failed', true))->error('');
        }

        if (!Auth::create($this->request)) {
            Redirect::back()->withInputs($validator->inputs())
                ->withAlert(__('user_already_exists', true))->error('');
        }

        if (config('security.auth.email_confirmation') === false) {
            EmailHelper::sendWelcome($this->request->email);
            Redirect::url('login')->withAlert(__('user_registered', true))->success('');
        } else {
            $token = random_string(50, true);

            if (EmailHelper::sendConfirmation($this->request->email, $token)) {
                TokensModel::insert([
                    'email' => $this->request->email,
                    'token' => $token,
                    'expires' => Carbon::now()->addDay()->toDateTimeString()
                ]);

                Redirect::url('login')->withAlert(__('confirm_email_link_sent', true))->success('');
            } else {
                Redirect::url('login')->withAlert(__('confirm_email_link_not_sent', true))->error('');
            }
        }
    }
	
	/**
	 * logout
	 *
	 * @return void
	 */
	public function logout(): void
	{
		Auth::forget();
		Redirect::url()->only();
	}
}
