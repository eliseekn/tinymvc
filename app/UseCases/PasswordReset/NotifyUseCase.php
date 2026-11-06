<?php

declare(strict_types=1);

namespace App\UseCases\PasswordReset;

use App\Database\Models\TokenModel;
use App\Enums\TokenDescription;
use App\Exceptions\InvalidDataException;
use App\Notifications\Mails\PasswordResetMail;
use Core\Http\Auth\Auth;
use Core\Notification\Notification;

final class NotifyUseCase
{
    public function handle(string $email, string $provider = 'user'): void
    {
        if (! Auth::hasProvider($provider)) {
            throw new InvalidDataException(__('alert.bad_request'));
        }

        $tokenValue = generate_token(15);
        TokenModel::generate($email, TokenDescription::PASSWORD_RESET->value, $tokenValue, $provider);

        Notification::send(new PasswordResetMail($email, $tokenValue, $provider))->to($email);
    }
}
