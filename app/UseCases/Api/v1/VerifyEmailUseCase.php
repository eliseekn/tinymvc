<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\Api\v1;

use App\Database\Models\Token;
use App\Database\Models\User;
use App\Enums\TokenDescription;
use App\Exceptions\Api\InvalidDataException;
use App\Notifications\Mails\WelcomeMail;
use Core\Notification\Notification;

final class VerifyEmailUseCase
{
    public function handle(): void
    {
        if (! request()->queries()->has(['email', 'token'])) {
            throw new InvalidDataException(__('alert.bad_request'));
        }

        $email = request()->queries()->get('email');
        $token = Token::findByDescription($email, TokenDescription::EMAIL_VERIFICATION->value);

        if (! $token || $token->getAttributes('value') !== request()->queries()->get('token')) {
            throw new InvalidDataException(__('alert.invalid_password_reset_link'));
        }

        if (carbon($token->getAttributes('expires_at'))->lt(carbon())) {
            throw new InvalidDataException(__('alert.expired_password_reset_link'));
        }

        $token->delete();

        $user = User::findByEmail($email)
            ->setAttributes(['email_verified_at' => carbon()->toDateTimeString()])
            ->save();

        if (! $user) {
            throw new InvalidDataException(__('alert.account_not_found'));
        }

        Notification::send(new WelcomeMail($user->getAttributes('name')))->to($email);
    }
}
