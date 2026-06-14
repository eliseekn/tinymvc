<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\EmailVerification;

use App\Database\Models\Token;
use App\Database\Models\User;
use App\Enums\TokenDescription;
use App\Exceptions\InvalidDataException;
use App\Exceptions\VerifyEmailException;
use App\Notifications\Mails\WelcomeMail;
use Core\Notification\Notification;

final class VerifyUseCase
{
    public function handle(): void
    {
        if (! request()->queries()->has(['email', 'token'])) {
            throw new InvalidDataException(__('alert.bad_request'));
        }

        $email = request()->queries()->get('email');
        $token = Token::findByDescription($email, TokenDescription::EMAIL_VERIFICATION->value);

        if (! $token || $token->get('value') !== request()->queries()->get('token')) {
            throw new InvalidDataException(__('alert.invalid_password_reset_link'));
        }

        if (carbon($token->get('expires_at'))->lt(carbon())) {
            throw new InvalidDataException(__('alert.expired_password_reset_link'));
        }

        $token->delete();

        $user = User::findByEmail($email)
            ->set(['email_verified_at' => carbon()->toDateTimeString()])
            ->save();

        if (! $user) {
            throw new VerifyEmailException;
        }

        Notification::send(new WelcomeMail($user->get('name')))->to($email);
    }
}
