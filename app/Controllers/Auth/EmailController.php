<?php

namespace App\Controllers\Auth;

use Carbon\Carbon;
use Framework\HTTP\Request;
use App\Helpers\EmailHelper;
use Framework\HTTP\Redirect;
use Framework\HTTP\Response;
use App\Middlewares\AuthPolicy;
use App\Database\Models\UsersModel;
use App\Database\Models\TokensModel;
use App\Helpers\AuthHelper;

/**
 * Manage email confirmation
 */
class EmailController
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
                'expires' => Carbon::now()->addHour()->format('Y-m-d H:i:s')
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
            Redirect::toUrl('/')->withError('Your account has been successfully activated.');
        } else {
            Redirect::toUrl('/signup')->withError('Your account is not registred. Please register here.');
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
			Response::send([], 'This Two-Factor authentication link is invalid');
		}

		if ($auth_token->expires < date('Y-m-d H:i:s')) {
			Response::send([], 'This Two-Factor authentication link expired. Please retrieves a new one');
		}

        TokensModel::delete()->where('email', $auth_token->email)->persist();

        AuthHelper::authEmail($auth_token->email);
        AuthPolicy::handle();
    }
}
