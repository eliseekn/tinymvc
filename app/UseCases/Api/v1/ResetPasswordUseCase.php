<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\Api\v1;

use App\Database\Models\TokenModel;
use App\Enums\TokenDescription;
use App\Exceptions\Api\InvalidDataException;

final class ResetPasswordUseCase
{
    public function handle(): void
    {
        if (! request()->queries()->has(['email', 'token'])) {
            throw new InvalidDataException(__('alert.bad_request'));
        }

        $email = request()->queries()->get('email');
        $token = TokenModel::findByDescription($email, TokenDescription::PASSWORD_RESET->value);

        if (! $token || $token->getValue() !== request()->queries()->get('token')) {
            throw new InvalidDataException(__('alert.invalid_password_reset_link'));
        }

        if ($token->isExpired()) {
            throw new InvalidDataException(__('alert.expired_password_reset_link'));
        }
    }
}
