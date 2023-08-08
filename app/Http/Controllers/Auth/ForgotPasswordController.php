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
use App\Mails\TokenMail;
use Core\Support\Mail\Mail;
use App\Database\Models\Token;
use App\Http\Actions\User\UpdateAction;
use App\Http\Validators\Auth\LoginValidator;

/**
 * Manage password forgot
 */
class ForgotPasswordController extends Controller
{
	public function notify(): void
	{
		$token = generate_token();

        if (Mail::send(new TokenMail($this->request->get('email'), $token))) {
            if (
                !Token::where('email', $this->request->queries('email'))
                    ->and('description', TokenDescription::PASSWORD_RESET_TOKEN->value)
                    ->exists()
            ) {
                Token::create([
                    'email'=> $this->request->queries('email'),
                    'value' => $token,
                    'expire' => Carbon::now()->addHour()->toDateTimeString(),
                    'description' => TokenDescription::PASSWORD_RESET_TOKEN->value
                ]);
            } else {
                Token::where('email', $this->request->queries('email'))
                    ->and('description', TokenDescription::PASSWORD_RESET_TOKEN->value)
                    ->update(['value' => $token]);
            }

            Alert::default(__('password_reset_link_sent'))->success();
			$this->back();
		}
        
        Alert::default(__('password_reset_link_not_sent'))->error();
        $this->back();
	}
	
	public function reset(): void
	{
        if (!$this->request->hasQuery(['email', 'token'])) {
            $this->data(__('bad_request'), 400);
        }

        $token = Token::where('email', $this->request->queries('email'))
            ->and('description', TokenDescription::PASSWORD_RESET_TOKEN->value)
            ->newest()
            ->first();

        if (!$token || $token->value !== $this->request->queries('token')) {
			$this->data(__('invalid_password_reset_link'), 400);
		}

		if (Carbon::parse($token->expire)->lt(Carbon::now())) {
			$this->data(__('expired_password_reset_link'), 400);
		}

        $token->delete();
        $this->redirect('/password/new', ['email' => $this->request->queries('email')]);
	}
	
	public function update(LoginValidator $validator, UpdateAction $action): void
	{
        $validator->validate($this->request->inputs(), $this->response);
        $user = $action->handle(['password' => $this->request->get('password')], $this->request->get('email'));

        if (!$user) {
            Alert::default(__('password_not_reset'))->error();
            $this->back();
        }

        Alert::default(__('password_reset'))->success();
        $this->redirect('/login');
	}
}
