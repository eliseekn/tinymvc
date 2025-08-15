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
use Core\Support\Alert;
use Exception;

final class NotifyUseCase
{
    public function handle(string $email): void
    {
        $tokenValue = generate_token(15);
        $token = Token::findByDescription($email, TokenDescription::EMAIL_VERIFICATION->value);

        if ($token) {
            $token->update(['value' => $tokenValue]);
        } else {
            $token = (new Token)->create([
                'identifier' => $email,
                'value' => $tokenValue,
                'expires_at' => carbon()->addDay()->toDateTimeString(),
                'description' => TokenDescription::EMAIL_VERIFICATION->value,
            ]);
        }

        try {
            Notification::send(new VerificationMail($email, $tokenValue))->to($email);
            Alert::default(__('alert.password_reset_link_sent'))->success();
        } catch (Exception $e) {
            report($e);
            $token->delete();
            Alert::default(__('alert.email_verification_link_not_sent'))->error();
            response()->url('/signup')->send();
        }

        Alert::default(__('alert.email_verification_link_sent'))->success();
        response()->url('/email/notify')->send();
    }
}
