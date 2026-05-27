<?php

declare(strict_types=1);

namespace App\UseCases\PasswordReset;

use App\Database\Models\Token;
use App\Enums\TokenDescription;
use App\Notifications\Mails\PasswordResetMail;
use Core\Notification\Notification;
use Exception;

final class NotifyUseCase
{
    public function handle(string $email): bool
    {
        $tokenValue = generate_token(15);
        $token = Token::generate($email, TokenDescription::PASSWORD_RESET->value, $tokenValue);

        try {
            Notification::send(new PasswordResetMail($email, $tokenValue))->to($email);

            return true;
        } catch (Exception $e) {
            report($e);

            $token->delete();

            return false;
        }
    }
}
