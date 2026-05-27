<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\Api\v1\Auth;

use App\Database\Models\User;
use App\Exceptions\InvalidCredentialsException;
use Core\Http\Auth;
use Core\Support\Encryption;

final class LoginUseCase
{
    public function handle(array $data): array
    {
        $user = User::findByEmail($data['email']);

        if (! $user || ! Encryption::check($data['password'], $user->get('password'))) {
            throw new InvalidCredentialsException;
        }

        return [
            'token' => Auth::createToken($user),
            'user' => $user->get(),
        ];
    }
}
