<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Auth;

use App\Database\Models\Token;
use App\Enums\TokenDescription;
use App\Http\UseCases\User\UpdateUseCase;
use App\Http\Validators\Auth\LoginValidator;
use App\Mails\TokenMail;
use Core\Routing\Attributes\Route;
use Core\Routing\Controller;
use Core\Support\Alert;

/**
 * Manage password forgot
 */
class ForgotPasswordController extends Controller
{
    #[Route('POST', '/password/notify', ['csrf'])]
	public function notify(): void
	{
        $tokenValue = generate_token(15);
        $token = Token::findByDescription($this->request->inputs('email'), TokenDescription::PASSWORD_RESET_TOKEN->value);

        if ($token) {
            $token->update(['value' => $tokenValue]);
        } else {
            (new Token())->create([
                'email'=> $this->request->inputs('email'),
                'value' => $tokenValue,
                'expires_at' => carbon()->addHour()->toDateTimeString(),
                'description' => TokenDescription::PASSWORD_RESET_TOKEN->value
            ]);
        }

        if (!TokenMail::send($this->request->inputs('email'), $tokenValue)) {
            Alert::default(__('password_reset_link_not_sent'))->error();
        } else {
            Alert::default(__('password_reset_link_sent'))->success();
        }

        $this->redirectBack();
	}

    #[Route('GET', '/password/reset')]
	public function reset(): void
	{
        if (!$this->request->hasQuery(['email', 'token'])) {
            $this->response(__('bad_request'), 400);
        }

        $token = Token::findByDescription($this->request->queries('email'), TokenDescription::PASSWORD_RESET_TOKEN->value);

        if (!$token || $token->get('value') !== $this->request->queries('token')) {
			$this->response(__('invalid_password_reset_link'), 400);
		}

		if (carbon($token->get('expires_at'))->lt(carbon())) {
			$this->response(__('expired_password_reset_link'), 400);
		}

        $token->delete();
        $this->render('auth.password.new', ['email' => $this->request->queries('email')]);
	}

    #[Route('POST', '/password/update', ['csrf'])]
	public function update(UpdateUseCase $useCase): void
	{
        $validated = $this->validate(new LoginValidator());
        $user = $useCase->handle(['password' => $validated['password']], $validated['email']);

        if (!$user) {
            Alert::default(__('password_not_reset'))->error();
            $this->redirectBack();
        }

        Alert::default(__('password_reset'))->success();
        $this->redirectUrl('/login');
	}
}
