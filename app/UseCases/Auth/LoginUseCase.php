<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\Auth;

use App\Database\Entities\User;
use App\Exceptions\InvalidCredentialsException;
use Core\Http\Auth\Auth;

final class LoginUseCase
{
    public function handle(?User &$user): void
    {
        if (! Auth::attempt(new User, $user)) {
            throw new InvalidCredentialsException;
        }
    }
}
