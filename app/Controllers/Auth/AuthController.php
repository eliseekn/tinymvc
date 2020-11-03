<?php

namespace App\Controllers\Auth;

use Carbon\Carbon;
use App\Helpers\AuthHelper;
use Framework\HTTP\Request;
use App\Helpers\EmailHelper;
use App\Requests\AuthRequest;
use Framework\Support\Session;
use App\Middlewares\AuthPolicy;
use App\Requests\RegisterRequest;
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
        $validate = AuthRequest::validate(Request::getFields());
        
        if (is_array($validate)) {
            $this->redirect()->withError($validate);
        }

        AuthHelper::authenticate();
        AuthPolicy::handle();
    }
        
    /**
     * register new user
     *
     * @return void
     */
    public function register(): void
    {
        $validate = RegisterRequest::validate(Request::getFields());
        
        if (is_array($validate)) {
            $this->redirect()->withError($validate);
        }

        if (!AuthHelper::store()) {
            $this->redirect()->withError(__('user_already_exists', true));
        }

        if (config('security.auth.email_confirmation') === false) {
            EmailHelper::sendWelcome(Request::getField('email'));
            $this->redirect('/login')->withSuccess(__('user_registered', true));
        } else {
            $token = random_string(50, true);

            if (EmailHelper::sendConfirmation(Request::getField('email'), $token)) {
                TokensModel::insert([
                    'email' => Request::getField('email'),
                    'token' => $token,
                    'expires' => Carbon::now()->addHour()->toDateTimeString()
                ]);

                $this->redirect()->withInfo(__('confirm_email_link_sent', true));
            } else {
                $this->redirect()->withError(__('confirm_email_link_not_sent', true));
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
        Session::clearHistory();
        Session::close('csrf_token');
		$this->redirect('/')->only();
	}
}
