<?php

declare(strict_types=1);

namespace App\UseCases\PasswordReset;

use App\Database\Models\Token;
use App\Enums\TokenDescription;
use Core\Enums\HttpCode;

final class ResetUseCase
{
    public function handle(): void
    {
        if (! request()->hasQuery(['email', 'token'])) {
            response()->data(__('alert.bad_request'))->send(HttpCode::BAD_REQUEST);
        }

        $email = request()->queries('email');
        $token = Token::findByDescription($email, TokenDescription::PASSWORD_RESET->value);

        if (! $token || $token->get('value') !== request()->queries('token')) {
            response()->data(__('alert.invalid_password_reset_link'))->send(HttpCode::BAD_REQUEST);
        }

        if (carbon($token->get('expires_at'))->lt(carbon())) {
            response()->data(__('alert.expired_password_reset_link'))->send(HttpCode::BAD_REQUEST);
        }

        $token->delete();
        response()->view('auth.password.new', compact('email'))->send(HttpCode::OK);
    }
}
