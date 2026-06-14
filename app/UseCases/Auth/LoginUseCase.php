<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\Auth;

use App\Database\Models\User;
use App\Exceptions\InvalidCredentialsException;
use Core\Database\Model;
use Core\Http\Auth;

final class LoginUseCase
{
    public function handle(array $data, ?Model &$user): void
    {
        $user = User::findByEmail($data['email']);

        if (! Auth::attempt($user)) {
            throw new InvalidCredentialsException;
        }
    }
}
