<?php

namespace App\Controllers\Auth;

use Carbon\Carbon;
use App\Helpers\AuthHelper;
use App\Helpers\EmailHelper;
use App\Requests\AuthRequest;
use App\Requests\RegisterUser;
use App\Middlewares\AuthPolicy;
use Framework\Routing\Controller;
use App\Database\Models\TokensModel;
use Framework\Support\Validator;

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
            $this->redirectBack()->withErrors($validator->errors())->withInputs($validator->inputs())
                ->withAlert(__('login_failed', true))->error('');
        }

        AuthHelper::authenticate($this->request);
        AuthPolicy::handle();
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
            $this->redirectBack()->withErrors($validator->errors())->withInputs($validator->inputs())
                ->withAlert(__('signup_failed', true))->error('');
        }

        if (!AuthHelper::create($this->request)) {
            $this->redirectBack()->withInputs($validator->inputs())
                ->withAlert(__('user_already_exists', true))->error('');
        }

        if (config('security.auth.email_confirmation') === false) {
            EmailHelper::sendWelcome($this->request->email);
            $this->redirect('/login')->withAlert(__('user_registered', true))->success('');
        } else {
            $token = random_string(50, true);

            if (EmailHelper::sendConfirmation($this->request->email, $token)) {
                TokensModel::insert([
                    'email' => $this->request->email,
                    'token' => $token,
                    'expires' => Carbon::now()->addDay()->toDateTimeString()
                ]);

                $this->redirect('/login')->withAlert(__('confirm_email_link_sent', true))->success('');
            } else {
                $this->redirect('/login')->withAlert(__('confirm_email_link_not_sent', true))->error('');
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
		AuthHelper::forget();
		$this->redirect('/')->only();
	}
}
