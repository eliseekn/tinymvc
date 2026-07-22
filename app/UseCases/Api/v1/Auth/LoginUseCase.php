<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\Api\v1\Auth;

use App\Database\Models\UserModel;
use App\Exceptions\InvalidCredentialsException;
use Core\Http\Auth;
use Core\Support\Encryption;

final class LoginUseCase
{
    public function handle(array $data): array
    {
        $user = UserModel::findByEmail($data['email']);

        if (! $user || ! Encryption::check($data['password'], $user->getPassword())) {
            throw new InvalidCredentialsException;
        }

        return [
            'token' => Auth::createToken($user),
            'user' => $user->toArray(),
        ];
    }
}
