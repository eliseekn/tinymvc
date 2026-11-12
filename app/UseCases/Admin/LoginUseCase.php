<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\Admin;

use App\Database\Entities\Admin;
use App\Exceptions\InvalidCredentialsException;
use Core\Http\Auth\Auth;

final class LoginUseCase
{
    public function handle(array $data, ?Admin &$admin): void
    {
        if (! Auth::attempt(new Admin, $data, $admin)) {
            throw new InvalidCredentialsException;
        }
    }
}
