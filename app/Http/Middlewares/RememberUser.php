<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Middlewares;

use App\Database\Models\UserModel;

/**
 * Check for stored user cookie.
 */
class RememberUser
{
    public function handle(): void
    {
        if (cookies()->has('user')) {
            $user = UserModel::findByEmail(cookies()->get('user'));

            if ($user) {
                session()->create('user', $user->toArray());
            }
        }
    }
}
