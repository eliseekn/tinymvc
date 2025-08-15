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
use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;
use Core\Http\Auth;
use Core\Support\Encryption;

final class LoginUseCase
{
    public function handle(array $data): void
    {
        $user = User::findByEmail($data['email']);

        if (! $user || ! Encryption::check($data['password'], $user->get('password'))) {
            response()->json([
                'status' => ResponseStatus::ERROR,
                'message' => 'Email or password is incorrect',
            ])->send(HttpCode::UNAUTHORIZED);
        }

        response()->json([
            'status' => ResponseStatus::SUCCESS,
            'token' => Auth::createToken($user),
            'user' => $user->get(),
        ])->send(HttpCode::OK);
    }
}
