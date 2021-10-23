<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Core\Http\Request;
use Core\Support\Alert;
use App\Mails\TokenMail;
use App\Database\Models\Token;
use App\Http\Actions\UserActions;
use App\Http\Validators\Auth\LoginValidator;
use Core\Http\Response\Response;
use Core\Support\Mailer\Mailer;

/**
 * Manage password forgot
 */
class ForgotPasswordController
{
	public function notify(Request $request, Response $response, Mailer $mailer)
	{
		$token = generate_token();

		if (TokenMail::send($mailer, $request->email, $token)) {
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
            $response->send(__('bad_request'), [], 400);
        }

        $token = Token::findBy('email', $request->email);

        if (!$token || $token->token !== $request->token) {
			$response->send(__('invalid_password_reset_link'), [], 400);
		}

		if (Carbon::parse($token->expire)->lt(Carbon::now())) {
			$response->send(__('expired_password_reset_link'), [], 400);
		}

        $token->delete();
        $response->redirect()->to("password/new?email={$request->email}")->go();
	}
	
	public function update(Request $request, Response $response)
	{
		LoginValidator::make($request->inputs())->redirectBackOnFail($response);
        $user = UserActions::update(['password' => $request->password], $request->email);

        if (!$user) {
            Alert::default(__('password_not_reset'))->error();
            $response->redirect()->back()->go();
        }

        Alert::default(__('password_reset'))->success();
        $response->redirect()->to('login')->go();
	}
}
