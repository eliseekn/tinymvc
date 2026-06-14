<?php

declare(strict_types=1);

namespace App\UseCases\PasswordReset;

use App\Database\Models\Token;
use App\Enums\TokenDescription;
use App\Exceptions\InvalidDataException;

final class ResetUseCase
{
    public function handle(): void
    {
        if (! request()->queries()->has(['email', 'token'])) {
            throw new InvalidDataException(__('alert.bad_request'));
        }

        $email = request()->queries()->get('email');
        $token = Token::findByDescription($email, TokenDescription::PASSWORD_RESET->value);

        if (! $token || $token->get('value') !== request()->queries()->get('token')) {
            throw new InvalidDataException(__('alert.invalid_password_reset_link'));
        }

        if (carbon($token->get('expires_at'))->lt(carbon())) {
            throw new InvalidDataException(__('alert.expired_password_reset_link'));
        }
    }
}
