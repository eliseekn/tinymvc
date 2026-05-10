<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\UseCases\Api\v1;

use App\Database\Models\Token;
use App\Database\Models\User;
use App\Enums\TokenDescription;
use App\Notifications\Mails\WelcomeMail;
use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;
use Core\Notification\Notification;
use Exception;

final class VerifyEmailUseCase
{
    public function handle(): void
    {
        if (! request()->hasQuery(['email', 'token'])) {
            response()->json([
                'status' => ResponseStatus::ERROR,
                'message' => __('alert.bad_request'),
            ])->send(HttpCode::BAD_REQUEST);
        }

        $email = request()->queries('email');
        $token = Token::findByDescription($email, TokenDescription::EMAIL_VERIFICATION->value);

        if (! $token || $token->get('value') !== request()->queries('token')) {
            response()->json([
                'status' => ResponseStatus::ERROR,
                'message' => __('alert.invalid_password_reset_link'),
            ])->send(HttpCode::BAD_REQUEST);
        }

        if (carbon($token->get('expires_at'))->lt(carbon())) {
            response()->json([
                'status' => ResponseStatus::ERROR,
                'message' => __('alert.expired_password_reset_link'),
            ])->send(HttpCode::BAD_REQUEST);
        }

        $token->delete();

        $user = User::findByEmail($email)
            ->set(['email_verified_at' => carbon()->toDateTimeString()])
            ->save();

        if (! $user) {
            response()->json([
                'status' => ResponseStatus::ERROR,
                'message' => __('alert.account_not_found'),
            ])->send(HttpCode::BAD_REQUEST);
        }

        try {
            Notification::send(new WelcomeMail($user->get('name')))->to($email);

            response()->json([
                'status' => ResponseStatus::ERROR,
                'message' => __('alert.email_verified_at'),
            ])->send(HttpCode::OK);
        } catch (Exception $e) {
            report($e);

            response()->json([
                'status' => ResponseStatus::ERROR,
                'message' => __('alert.failed_email_verification'),
            ])->send(HttpCode::INTERNAL_SERVER_ERROR);
        }
    }
}
