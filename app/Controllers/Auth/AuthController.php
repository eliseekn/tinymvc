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
        $validate = AuthRequest::validate($this->request->inputs());
        
        if ($validate->fails()) {
            $this->redirectBack()->withError($validate->errors());
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
        $validate = RegisterUser::validate($this->request->inputs());
        
        if ($validate->fails()) {
            $this->redirectBack()->withError($validate->errors());
        }

        if (!AuthHelper::create($this->request)) {
            $this->redirectBack()->withError(__('user_already_exists', true));
        }

        if (config('security.auth.email_confirmation') === false) {
            EmailHelper::sendWelcome($this->request->email);
            $this->redirect('/login')->withSuccess(__('user_registered', true));
        } else {
            $token = random_string(50, true);

            if (EmailHelper::sendConfirmation($this->request->email, $token)) {
                TokensModel::insert([
                    'email' => $this->request->email,
                    'token' => $token,
                    'expires' => Carbon::now()->addDay()->toDateTimeString()
                ]);

                $this->redirectBack()->withInfo(__('confirm_email_link_sent', true));
            } else {
                $this->redirectBack()->withError(__('confirm_email_link_not_sent', true));
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
