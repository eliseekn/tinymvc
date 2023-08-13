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
use App\Http\Validators\Auth\LoginValidator;
use App\Mails\TokenMail;
use Carbon\Carbon;
use Core\Routing\Controller;
use Core\Support\Alert;
use Core\Support\Mail\Mail;

/**
 * Manage password forgot
 */
class ForgotPasswordController extends Controller
{
	public function notify(): void
	{
        $tokenValue = generate_token();

        if (!Mail::send(new TokenMail($this->request->get('email'), $tokenValue))) {
            Alert::default(__('password_reset_link_not_sent'))->error();
            $this->redirectBack();
        }

        $token = Token::exists($this->request->queries('email'), TokenDescription::PASSWORD_RESET_TOKEN->value);

        if ($token) {
            $token->update(['value' => $tokenValue]);
        } else {
            $token->fill([
                'email'=> $this->request->queries('email'),
                'value' => $token,
                'expire' => Carbon::now()->addHour()->toDateTimeString(),
                'description' => TokenDescription::PASSWORD_RESET_TOKEN->value
            ])->save();
        }

        Alert::default(__('password_reset_link_sent'))->success();
        $this->redirectBack();
	}
	
	public function reset(): void
	{
        if (!$this->request->hasQuery(['email', 'token'])) {
            $this->response(__('bad_request'), 400);
        }

        $token = Token::findLatest($this->request->queries('email'), TokenDescription::PASSWORD_RESET_TOKEN->value);

        if (!$token || $token->attribute('value') !== $this->request->queries('token')) {
			$this->response(__('invalid_password_reset_link'), 400);
		}

		if (Carbon::parse($token->attribute('expire'))->lt(Carbon::now())) {
			$this->response(__('expired_password_reset_link'), 400);
		}

        $token->delete();
        $this->render('auth.password.new', ['email' => $this->request->queries('email')]);
	}
	
	public function update(UpdateAction $action): void
	{
        $validated = $this->validateRequest(new LoginValidator());
        $user = $action->handle(['password' => $validated['password']], $validated['email']);

        if (!$user) {
            Alert::default(__('password_not_reset'))->error();
            $this->redirectBack();
        }

        Alert::default(__('password_reset'))->success();
        $this->redirectUrl('/login');
	}
}
