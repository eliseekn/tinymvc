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
use App\Enums\TokenDescription;
use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;

final class ResetPasswordUseCase
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
        $token = Token::findByDescription($email, TokenDescription::PASSWORD_RESET->value);

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

        response()->json([
            'status' => ResponseStatus::SUCCESS,
            'message' => __('alert.valid_password_reset_link'),
        ])->send(HttpCode::OK);
    }
}
