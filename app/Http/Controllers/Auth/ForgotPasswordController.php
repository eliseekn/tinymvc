<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Auth;

use App\Enums\TokenDescription;
use Carbon\Carbon;
use Core\Http\Request;
use Core\Support\Alert;
use App\Mails\TokenMail;
use Core\Support\Mail\Mail;
use App\Database\Models\Token;
use Core\Http\Response;
use App\Http\Actions\User\UpdateAction;
use App\Http\Validators\Auth\LoginValidator;

/**
 * Manage password forgot
 */
class ForgotPasswordController
{
	public function notify(Request $request, Response $response): void
	{
		$token = generate_token();

        if (Mail::send(new TokenMail($request->email, $token))) {
            if (
                !Token::where('email', $request->email)
                    ->and('description', TokenDescription::PASSWORD_RESET_TOKEN->value)
                    ->exists()
            ) {
                Token::create([
                    'email'=> $request->email,
                    'value' => $token,
                    'expire' => Carbon::now()->addHour()->toDateTimeString(),
                    'description' => TokenDescription::PASSWORD_RESET_TOKEN->value
                ]);
            } else {
                Token::where('email', $request->email)
                    ->and('description', TokenDescription::PASSWORD_RESET_TOKEN->value)
                    ->update(['value' => $token]);
            }

            Alert::default(__('password_reset_link_sent'))->success();
			$response->back()->send();
		}
        
        Alert::default(__('password_reset_link_not_sent'))->error();
        $response->back()->send();
	}
	
	public function reset(Request $request, Response $response): void
	{
        if (!$request->hasQuery(['email', 'token'])) {
            $response->data(__('bad_request'))->send(400);
        }

        $token = Token::findBy('email', $request->email);

        if (!$token || $token->value !== $request->token) {
			$response->data(__('invalid_password_reset_link'))->send(400);
		}

		if (Carbon::parse($token->expire)->lt(Carbon::now())) {
			$response->data(__('expired_password_reset_link'))->send(400);
		}

        $token->delete();
        $response->url("/password/new?email={$request->email}")->send();
	}
	
	public function update(Request $request, Response $response, LoginValidator $loginValidator, UpdateAction $updateAction): void
	{
        $loginValidator->validate($request->inputs(), $response);
        $user = $updateAction->handle(['password' => $request->password], $request->email);

        if (!$user) {
            Alert::default(__('password_not_reset'))->error();
            $response->back()->send();
        }

        Alert::default(__('password_reset'))->success();
        $response->url('/login')->send();
	}
}
