<?php

declare(strict_types=1);

namespace App\UseCases\PasswordReset;

use App\Database\Models\Token;
use App\Enums\TokenDescription;
use App\Notifications\Mails\PasswordResetMail;
use Core\Notification\Notification;

final class NotifyUseCase
{
    public function handle(string $email): void
    {
        $tokenValue = generate_token(15);
        Token::generate($email, TokenDescription::PASSWORD_RESET->value, $tokenValue);

        Notification::send(new PasswordResetMail($email, $tokenValue))->to($email);
    }
}
