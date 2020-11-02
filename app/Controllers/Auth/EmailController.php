<?php

namespace App\Controllers\Auth;

use Carbon\Carbon;
use App\Helpers\AuthHelper;
use Framework\HTTP\Request;
use App\Helpers\EmailHelper;
use App\Middlewares\AuthPolicy;
use Framework\Routing\Controller;
use App\Database\Models\UsersModel;
use App\Database\Models\TokensModel;

/**
 * Manage email confirmation
 */
class EmailController extends Controller
{
	/**
	 * send email confirmation
	 *
	 * @return void
	 */
	public function notify(): void
	{
        $token = random_string(50, true);
            
		if (EmailHelper::sendConfirmation(Request::getField('email'), $token)) {
            TokensModel::insert([
                'email' => Request::getField('email'),
                'token' => $token,
                'expires' => Carbon::now()->addHour()->toDateTimeString()
            ]);
        }
	}
	
	/**
	 * verify email confirmation link
	 *
	 * @return void
	 */
	public function verify(): void
	{
		if (UsersModel::find('email', Request::getQuery('email'))->exists()) {
            UsersModel::update(['active' => 1])->where('email', Request::getQuery('email'))->persist();
            EmailHelper::sendWelcome(Request::getField('email'));
            $this->redirect('/')->withError('Your account has been successfully activated.');
        } else {
            $this->redirect('/signup')->withError('Your account is not registred. Please register here.');
        }
    }
        
    /**
     * auth user from email link
     *
     * @return void
     */
    public function auth(): void
    {
        $auth_token = TokensModel::find('email', Request::getQuery('email'))->single();

        if ($auth_token === false || $auth_token->token !== Request::getQuery('token')) {
			$this->response('This Two-Factor authentication link is invalid');
		}

		if ($auth_token->expires < Carbon::now()->toDateTimeString()) {
			$this->response('This Two-Factor authentication link expired. Please retrieves a new one');
		}

        TokensModel::delete()->where('email', $auth_token->email)->persist();

        AuthHelper::authEmail($auth_token->email);
        AuthPolicy::handle();
    }
}
