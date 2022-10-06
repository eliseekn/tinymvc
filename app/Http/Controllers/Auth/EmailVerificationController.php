<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Auth;

use App\Http\UseCases\User\UpdateUseCase;
use Carbon\Carbon;
use Core\Http\Request;
use Core\Support\Alert;
use App\Mails\WelcomeMail;
use Core\Support\Mail\Mail;
use App\Database\Models\Token;
use App\Mails\VerificationMail;
use Core\Http\Response;

/**
 * Manage email verification link
 */
final class EmailVerificationController
{
    public function notify(Request $request, Response $response)
    {
        $token = generate_token();

        if (Mail::send(new VerificationMail($request->query('email'), $token))) {
            Token::create([
                'email'=> $request->input('email'),
                'token' => $token,
                'expire' => Carbon::now()->addDay()->toDateTimeString()
            ]);

            Alert::default(__('email_verification_link_sent'))->success();
            $response->redirect('login')->send(302);
        }
        
        Alert::default(__('email_verification_link_not_sent'))->error();
        $response->redirect('signup')->send(302);
    }

	public function verify(Request $request, Response $response, UpdateUseCase $useCase)
	{
        if (!$request->hasQuery('email', 'token')) {
            $response->data(__('bad_request'))->send(400);
        }

        $token = Token::findBy('email', $request->query('email'));

        if (!$token || $token->token !== $request->query('token')) {
			$response->data(__('invalid_email_verification_link'))->send(400);
		}

		if (Carbon::parse($token->expire)->lt(Carbon::now())) {
            $token->delete();
            $response->data(__('expired_email_verification_link'))->send(400);
		}

        $token->delete();

        $user = $useCase->handleByEmail(['email_verified' => Carbon::now()->toDateTimeString()], $request->query('email'));

		if (!$user) {
            Alert::default(__('account_not_found'))->error();
            $response->redirect('signup')->send(400);
        }

        Mail::send(new WelcomeMail($user->email, $user->name));

        Alert::default(__('email_verified'))->success();
        $response->redirect('login')->send(400);
    }
}
