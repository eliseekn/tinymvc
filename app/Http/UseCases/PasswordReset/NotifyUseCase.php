<?php

declare(strict_types=1);

namespace App\Http\UseCases\PasswordReset;

use App\Database\Models\Token;
use App\Enums\TokenDescription;
use App\Mails\PasswordResetMail;
use Core\Notification\Notification;
use Core\Support\Alert;
use Core\Support\UseCase;
use Exception;

final class NotifyUseCase extends UseCase
{
    public function handle(): void
    {
        $tokenValue = generate_token(15);
        $email = $this->request->inputs('email');
        $token = Token::findByDescription($email, TokenDescription::PASSWORD_RESET);

        if ($token) {
            $token->update(['value' => $tokenValue]);
        } else {
            $token = (new Token)->create([
                'email' => $email,
                'value' => $tokenValue,
                'expires_at' => carbon()->addHour()->toDateTimeString(),
                'description' => TokenDescription::PASSWORD_RESET,
            ]);
        }

        try {
            Notification::send(new PasswordResetMail($email, $tokenValue))->to($email);
            Alert::default(__('alert.password_reset_link_sent'))->success();
        } catch (Exception $e) {
            report($e);
            $token->delete();
            Alert::default(__('alert.password_reset_link_not_sent'))->success();
        }

        $this->response->back()->send();
    }
}
