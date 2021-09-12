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
use Core\Support\Mailer\Mailer;
use App\Mails\WelcomeMail;
use App\Database\Models\User;
use App\Database\Models\Token;
use App\Mails\VerificationMail;
use Core\Http\Response\Response;

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
        $response->redirect()->back()->go();
    }

	public function verify(Request $request, Response $response, Mailer $mailer)
	{
        $user = User::findBy('email', $request->queries('email'));

		if (!$user) {
            Alert::default(__('account_not_found'))->error();
            $response->redirect()->to('signup')->go();
        }

        $user->verified = 1;
        $user = $user->save();

        WelcomeMail::send($mailer, $user->email, $user->name);

        Alert::default(__('verified'))->success();
        $response->redirect()->to('login')->go();
    }
}
