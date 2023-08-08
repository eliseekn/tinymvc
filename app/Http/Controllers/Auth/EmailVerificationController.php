<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Auth;

use App\Enums\TokenDescription;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Core\Support\Alert;
use App\Mails\WelcomeMail;
use Core\Support\Mail\Mail;
use App\Database\Models\Token;
use App\Mails\VerificationMail;
use App\Http\Actions\User\UpdateAction;

/**
 * Manage email verification link
 */
class EmailVerificationController extends Controller
{
    public function notify(): void
    {
        $token = generate_token();

        if (Mail::send(new VerificationMail($this->request->queries('email'), $token))) {
            if (
                !Token::where('email', $this->request->queries('email'))
                    ->and('description', TokenDescription::EMAIL_VERIFICATION_TOKEN->value)
                    ->exists()
            ) {
                Token::create([
                    'email'=> $this->request->queries('email'),
                    'value' => $token,
                    'expire' => Carbon::now()->addDay()->toDateTimeString(),
                    'description' => TokenDescription::EMAIL_VERIFICATION_TOKEN->value
                ]);
            } else {
                Token::where('email', $this->request->queries('email'))
                    ->and('description', TokenDescription::EMAIL_VERIFICATION_TOKEN->value)
                    ->update(['value' => $token]);
            }

            Alert::default(__('email_verification_link_sent'))->success();
            $this->render('login');
        }
        
        Alert::default(__('email_verification_link_not_sent'))->error();
        $this->render('signup');
    }

	public function verify(UpdateAction $action): void
	{
        if (!$this->request->hasQuery(['email', 'token'])) {
            $this->data(__('bad_request'), 400);
        }

        $token = Token::where('email', $this->request->queries('email'))
            ->and('description', TokenDescription::EMAIL_VERIFICATION_TOKEN->value)
            ->newest()
            ->first();

        if (!$token || $token->value !== $this->request->queries('token')) {
			$this->data(__('invalid_password_reset_link'), 400);
		}

		if (Carbon::parse($token->expire)->lt(Carbon::now())) {
			$this->data(__('expired_password_reset_link'), 400);
		}

        $token->delete();
        $user = $action->handle(['email_verified' => Carbon::now()->toDateTimeString()], $this->request->queries('email'));

		if (!$user) {
            Alert::default(__('account_not_found'))->error();
            $this->redirect('/signup');
        }

        Mail::send(new WelcomeMail($user->attribute('email'), $user->attribute('name')));
        Alert::default(__('email_verified'))->success();

        $this->redirect('/login');
    }
}
