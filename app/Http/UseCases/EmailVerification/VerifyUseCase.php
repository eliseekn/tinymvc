<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\UseCases\EmailVerification;

use App\Database\Models\Token;
use App\Enums\TokenDescription;
use App\Http\UseCases\User\UpdateUseCase;
use App\Mails\WelcomeMail;
use Core\Enums\HttpCode;
use Core\Notification\Notification;
use Core\Support\Alert;
use Core\Support\UseCase;
use Exception;

final class VerifyUseCase extends UseCase
{
    public function handle(UpdateUseCase $updateUseCase): void
    {
        if (! $this->request->hasQuery(['email', 'token'])) {
            $this->response->data(__('alert.bad_request'))->send(HttpCode::BAD_REQUEST);
        }

        $email = $this->request->queries('email');
        $token = Token::findByDescription($email, TokenDescription::EMAIL_VERIFICATION);

        if (! $token || $token->get('value') !== $this->request->queries('token')) {
            $this->response->data(__('alert.invalid_password_reset_link'))->send(HttpCode::BAD_REQUEST);
        }

        if (carbon($token->get('expires_at'))->lt(carbon())) {
            $this->response->data(__('alert.expired_password_reset_link'))->send(HttpCode::BAD_REQUEST);
        }

        $token->delete();
        $user = $updateUseCase->handle(['email_verified_at' => carbon()->toDateTimeString()], $email);

        if (! $user) {
            Alert::default(__('alert.account_not_found'))->error();
            $this->response->url('/signup')->send();
        }

        try {
            Notification::send(new WelcomeMail($user->get('name')))->to($email);
            Alert::toast(__('alert.email_verified_at'))->success();
        } catch (Exception $e) {
            report($e);
        }

        $this->response->url('/login')->send();
    }
}
