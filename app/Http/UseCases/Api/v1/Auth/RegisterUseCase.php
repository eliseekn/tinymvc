<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\UseCases\Api\v1\Auth;

use App\Database\Models\User;
use App\Events\UserRegistered\UserRegisteredEvent;
use App\Http\UseCases\EmailVerification\NotifyUseCase;
use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;

final class RegisterUseCase
{
    public function handle(array $data, NotifyUseCase $notifyUseCase): void
    {
        $data['password'] = bcrypt($data['password']);

        $user = User::factory()->create($data);

        dispatch(new UserRegisteredEvent($user));

        if (config('security.auth.email_verification') && $notifyUseCase->handle($user->get('email'))) {
            response()->json([
                'status' => ResponseStatus::SUCCESS,
                'message' => __('alert.account_created'),
            ])->send(HttpCode::OK);
        }

        response()->json([
            'status' => ResponseStatus::ERROR,
            'message' => __('alert.account_not_created'),
        ])->send(HttpCode::INTERNAL_SERVER_ERROR);
    }
}
