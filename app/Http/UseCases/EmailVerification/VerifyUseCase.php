<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\UseCases\EmailVerification;

use App\Database\Models\Token;
use App\Enums\TokenDescription;
use App\Http\UseCases\User\UpdateUseCase;
use App\Mails\WelcomeMail;
use Core\Notifications\Notification;
use Core\Support\Alert;
use Core\Support\UseCase;
use Exception;

class VerifyUseCase extends UseCase
{
    public function handle(UpdateUseCase $updateUseCase): void
    {
        if (! $this->request->hasQuery(['email', 'token'])) {
            $this->response->data(__('bad_request'))->send(400);
        }

        $email = $this->request->queries('email');
        $token = Token::findByDescription($email, TokenDescription::EMAIL_VERIFICATION);

        if (! $token || $token->get('value') !== $this->request->queries('token')) {
            $this->response->data(__('invalid_password_reset_link'))->send(400);
        }

        if (carbon($token->get('expires_at'))->lt(carbon())) {
            $this->response->data(__('expired_password_reset_link'))->send(400);
        }

        $token->delete();
        $user = $updateUseCase->handle(['email_verified_at' => carbon()->toDateTimeString()], $email);

        if (! $user) {
            Alert::default(__('account_not_found'))->error();
            $this->response->url('/signup')->send();
        }

        try {
            Notification::send(new WelcomeMail($user->get('name')))->to($email);
            Alert::default(__('email_verified_at'))->success();
        } catch (Exception $e) {
            report($e);
        }

        $this->response->url('/login')->send();
    }
}
