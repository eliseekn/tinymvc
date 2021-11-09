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
use App\Mails\WelcomeMail;
use App\Database\Models\Token;
use App\Mails\VerificationMail;
use Core\Support\Mailer\Mailer;
use Core\Http\Response\Response;
use App\Http\Actions\UserActions;

/**
 * Manage email verification link
 */
class EmailVerificationController
{
    public function notify(Request $request, Response $response, Mailer $mailer)
    {
        $token = generate_token();

        if (VerificationMail::send($mailer, $request->email, $token)) {
            Token::create([
                'email'=> $request->email,
                'token' => $token,
                'expire' => Carbon::now()->addDay()->toDateTimeString()
            ]);

            Alert::default(__('email_verification_link_sent'))->success();
            $response->redirect()->to('login')->go();
        }
        
        Alert::default(__('email_verification_link_not_sent'))->error();
        $response->redirect()->to('signup')->go();
    }

	public function verify(Request $request, Response $response, Mailer $mailer)
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

        $user = UserActions::update(['email_verified' => Carbon::now()->toDateTimeString()], $request->queries('email'));

		if (!$user) {
            Alert::default(__('account_not_found'))->error();
            $response->redirect()->to('signup')->go();
        }

        WelcomeMail::send($mailer, $user->email, $user->name);

        Alert::default(__('email_verified'))->success();
        $response->redirect()->to('login')->go();
    }
}
