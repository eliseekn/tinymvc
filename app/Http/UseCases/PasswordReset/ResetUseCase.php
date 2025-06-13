<?php

declare(strict_types=1);

namespace App\Http\UseCases\PasswordReset;

use App\Database\Models\Token;
use App\Enums\TokenDescription;
use Core\Enums\HttpCode;
use Core\Support\UseCase;

final class ResetUseCase extends UseCase
{
    public function handle(): void
    {
        if (! $this->request->hasQuery(['email', 'token'])) {
            $this->response->data(__('alert.bad_request'))->send(HttpCode::BAD_REQUEST);
        }

        $email = $this->request->queries('email');
        $token = Token::findByDescription($email, TokenDescription::PASSWORD_RESET->value);

        if (! $token || $token->get('value') !== $this->request->queries('token')) {
            $this->response->data(__('alert.invalid_password_reset_link'))->send(HttpCode::BAD_REQUEST);
        }

        if (carbon($token->get('expires_at'))->lt(carbon())) {
            $this->response->data(__('alert.expired_password_reset_link'))->send(HttpCode::BAD_REQUEST);
        }

        $token->delete();
        $this->response->view('auth.password.new', compact('email'))->send();
    }
}
