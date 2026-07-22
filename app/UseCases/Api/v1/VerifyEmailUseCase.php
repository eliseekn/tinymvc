<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\Api\v1;

use App\Database\Models\TokenModel;
use App\Database\Models\UserModel;
use App\Enums\TokenDescription;
use App\Exceptions\Api\InvalidDataException;
use App\Notifications\Mails\WelcomeMail;
use Carbon\Carbon;
use Core\Notification\Notification;

final class VerifyEmailUseCase
{
    public function handle(): void
    {
        if (! request()->queries()->has(['email', 'token'])) {
            throw new InvalidDataException(__('alert.bad_request'));
        }

        $email = request()->queries()->get('email');
        $token = TokenModel::findByDescription($email, TokenDescription::EMAIL_VERIFICATION->value);

        if (! $token || $token->getValue() !== request()->queries()->get('token')) {
            throw new InvalidDataException(__('alert.invalid_password_reset_link'));
        }

        if ($token->isExpired()) {
            throw new InvalidDataException(__('alert.expired_password_reset_link'));
        }

        $token->toModel()->delete();

        $user = UserModel::findByEmail($email);

        if (is_null($user)) {
            throw new InvalidDataException(__('alert.account_not_found'));
        }

        $user->setEmailVerifiedAt(Carbon::now());

        if ($token->toModel()->save()) {
            throw new InvalidDataException(__('alert.account_not_found'));
        }

        Notification::send(new WelcomeMail($user->getName()))->to($email);
    }
}
