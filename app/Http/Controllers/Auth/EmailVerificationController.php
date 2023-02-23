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
    public function notify(Request $request, Response $response)
    {
        $token = generate_token();

        if (Mail::send(new VerificationMail($request->email, $token))) {
            Token::create([
                'email'=> $request->email,
                'token' => $token,
                'expire' => Carbon::now()->addDay()->toDateTimeString()
            ]);

            Alert::default(__('email_verification_link_sent'))->success();
            $response->redirectUrl('login')->send(302);
        }
        
        Alert::default(__('email_verification_link_not_sent'))->error();
        $response->redirectUrl('signup')->send(302);
    }

	public function verify(Request $request, Response $response, UpdateAction $updateAction)
	{
        if (!$request->hasQuery('email', 'token')) {
            $response->data(__('bad_request'))->send(400);
        }

        $token = Token::findBy('email', $request->email);

        if (!$token || $token->token !== $request->token) {
			$response->data(__('invalid_password_reset_link'))->send(400);
		}

		if (Carbon::parse($token->expire)->lt(Carbon::now())) {
			$response->data(__('expired_password_reset_link'))->send(400);
		}

        $token->delete();

        $user = $updateAction->handle(['email_verified' => Carbon::now()->toDateTimeString()], $request->queries('email'));

		if (!$user) {
            Alert::default(__('account_not_found'))->error();
            $response->redirectUrl('signup')->send(400);
        }

        Mail::send(new WelcomeMail($user->email, $user->name));

        Alert::default(__('email_verified'))->success();
        $response->redirectUrl('login')->send(400);
    }
}
