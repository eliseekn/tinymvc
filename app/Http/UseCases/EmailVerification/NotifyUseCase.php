<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\UseCases\EmailVerification;

use App\Database\Models\Token;
use App\Enums\TokenDescription;
use App\Notifications\Mails\VerificationMail;
use Core\Notification\Notification;
use Exception;

final class NotifyUseCase
{
    public function handle(string $email): bool
    {
        $tokenValue = generate_token(15);
        $token = Token::generate($email, TokenDescription::EMAIL_VERIFICATION->value, $tokenValue);

        try {
            Notification::send(new VerificationMail($email, $tokenValue))->to($email);

            return true;
        } catch (Exception $e) {
            report($e);

            $token->delete();

            return false;
        }
    }
}
