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
use App\Database\Models\User;
use App\Enums\TokenDescription;
use App\Mails\WelcomeMail;
use Core\Enums\HttpCode;
use Core\Notification\Notification;
use Core\Support\Alert;
use Exception;

final class VerifyUseCase
{
    public function handle(): void
    {
        if (! request()->hasQuery(['email', 'token'])) {
            response()->data(__('alert.bad_request'))->send(HttpCode::BAD_REQUEST);
        }

        $email = request()->queries('email');
        $token = Token::findByDescription($email, TokenDescription::EMAIL_VERIFICATION->value);

        if (! $token || $token->get('value') !== request()->queries('token')) {
            response()->data(__('alert.invalid_password_reset_link'))->send(HttpCode::BAD_REQUEST);
        }

        if (carbon($token->get('expires_at'))->lt(carbon())) {
            response()->data(__('alert.expired_password_reset_link'))->send(HttpCode::BAD_REQUEST);
        }

        $token->delete();
        $user = User::findByEmail($email)->set(['email_verified_at' => carbon()->toDateTimeString()])->save();

        if (! $user) {
            Alert::default(__('alert.account_not_found'))->error();
            response()->url('/signup')->send();
        }

        try {
            Notification::send(new WelcomeMail($user->get('name')))->to($email);
            Alert::toast(__('alert.email_verified_at'))->success();
        } catch (Exception $e) {
            report($e);
        }

        response()->url('/login')->send();
    }
}
