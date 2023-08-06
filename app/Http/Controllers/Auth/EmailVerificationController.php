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
use App\Mails\WelcomeMail;
use Core\Support\Mail\Mail;
use App\Database\Models\Token;
use App\Mails\VerificationMail;
use Core\Http\Response;
use App\Http\Actions\User\UpdateAction;

/**
 * Manage email verification link
 */
class EmailVerificationController
{
    public function notify(Request $request, Response $response): void
    {
        $token = generate_token();

        if (Mail::send(new VerificationMail($request->email, $token))) {
            if (
                !Token::where('email', $request->email)
                    ->and('description', TokenDescription::EMAIL_VERIFICATION_TOKEN->value)
                    ->exists()
            ) {
                Token::create([
                    'email'=> $request->email,
                    'value' => $token,
                    'expire' => Carbon::now()->addDay()->toDateTimeString(),
                    'description' => TokenDescription::EMAIL_VERIFICATION_TOKEN->value
                ]);
            } else {
                Token::where('email', $request->email)
                    ->and('description', TokenDescription::EMAIL_VERIFICATION_TOKEN->value)
                    ->update(['value' => $token]);
            }

            Alert::default(__('email_verification_link_sent'))->success();
            $response->url('login')->send(302);
        }
        
        Alert::default(__('email_verification_link_not_sent'))->error();
        $response->url('signup')->send(302);
    }

	public function verify(Request $request, Response $response, UpdateAction $updateAction): void
	{
        if (!$request->hasQuery('email', 'token')) {
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

        $user = $updateAction->handle(['email_verified' => Carbon::now()->toDateTimeString()], $request->queries('email'));

		if (!$user) {
            Alert::default(__('account_not_found'))->error();
            $response->url('signup')->send(400);
        }

        Mail::send(new WelcomeMail($user->email, $user->name));

        Alert::default(__('email_verified'))->success();
        $response->url('login')->send(400);
    }
}
