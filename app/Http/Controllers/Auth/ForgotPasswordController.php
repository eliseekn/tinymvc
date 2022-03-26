<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Core\Http\Request;
use Core\Support\Alert;
use App\Mails\TokenMail;
use Core\Support\Mail\Mail;
use App\Database\Models\Token;
use Core\Http\Response\Response;
use App\Http\Actions\UserActions;
use App\Http\Validators\Auth\LoginValidator;

/**
 * Manage password forgot
 */
class ForgotPasswordController
{
	public function notify(Request $request, Response $response)
	{
		$token = generate_token();

        if (Mail::send(new TokenMail($request->email, $token))) {
            Token::create([
                'email'=> $request->email,
                'token' => $token,
                'expire' => Carbon::now()->addHour()->toDateTimeString()
            ]);

            Alert::default(__('password_reset_link_sent'))->success();
			$response->redirect()->back()->go();
		}
        
        Alert::default(__('password_reset_link_not_sent'))->error();
        $response->redirect()->back()->go();
	}
	
	public function reset(Request $request, Response $response)
	{
        if (!$request->hasQuery('email', 'token')) {
            $response->send(data: __('bad_request'), code: 400);
        }

        $token = Token::findBy('email', $request->email);

        if (!$token || $token->token !== $request->token) {
			$response->send(data: __('invalid_password_reset_link'), code: 400);
		}

		if (Carbon::parse($token->expire)->lt(Carbon::now())) {
			$response->send(data: __('expired_password_reset_link'), code: 400);
		}

        $token->delete();
        $response->redirect()->to("password/new?email={$request->email}")->go();
	}
	
	public function update(Request $request, Response $response, LoginValidator $loginValidator)
	{
        $loginValidator->validate($request->inputs(), $response);
        $user = UserActions::updatPassword($request->password, $request->email);

        if (!$user) {
            Alert::default(__('password_not_reset'))->error();
            $response->redirect()->back()->go();
        }

        Alert::default(__('password_reset'))->success();
        $response->redirect()->to('login')->go();
	}
}
