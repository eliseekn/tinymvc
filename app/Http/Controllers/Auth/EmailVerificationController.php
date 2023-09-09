<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Auth;

use App\Database\Models\Token;
use App\Enums\TokenDescription;
use App\Http\Actions\User\UpdateAction;
use App\Mails\VerificationMail;
use App\Mails\WelcomeMail;
use Core\Routing\Controller;
use Core\Support\Alert;

/**
 * Manage email verification link
 */
class EmailVerificationController extends Controller
{
    public function notify(): void
    {
        $tokenValue = generate_token(15);
        $token = Token::findByDescription($this->request->queries('email'), TokenDescription::EMAIL_VERIFICATION_TOKEN->value);

        if ($token) {
            $token->update(['value' => $tokenValue]);
        } else {
            (new Token())->create([
                'email'=> $this->request->queries('email'),
                'value' => $tokenValue,
                'expire' => carbon()->addDay()->toDateTimeString(),
                'description' => TokenDescription::EMAIL_VERIFICATION_TOKEN->value
            ]);
        }

        if (!VerificationMail::send($this->request->queries('email'), $tokenValue)) {
            Alert::default(__('email_verification_link_not_sent'))->error();
            $this->render('auth.signup');
        }

        Alert::default(__('email_verification_link_sent'))->success();
        $this->render('auth.login');
    }

	public function verify(UpdateAction $action): void
	{
        if (!$this->request->hasQuery(['email', 'token'])) {
            $this->response(__('bad_request'), 400);
        }

        $token = Token::findByDescription($this->request->queries('email'), TokenDescription::EMAIL_VERIFICATION_TOKEN->value);

        if (!$token || $token->getAttribute('value') !== $this->request->queries('token')) {
			$this->response(__('invalid_password_reset_link'), 400);
		}

		if (carbon($token->getAttribute('expire'))->lt(carbon())) {
			$this->response(__('expired_password_reset_link'), 400);
		}

        $token->delete();
        $user = $action->handle(['email_verified' => carbon()->toDateTimeString()], $this->request->queries('email'));

		if (!$user) {
            Alert::default(__('account_not_found'))->error();
            $this->redirectUrl('/signup');
        }

        WelcomeMail::send($user->getAttribute('email'), $user->getAttribute('name'));
        Alert::default(__('email_verified'))->success();

        $this->redirectUrl('/login');
    }
}
