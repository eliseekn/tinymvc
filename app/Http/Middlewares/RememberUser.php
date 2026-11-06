<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Middlewares;

use Core\Http\Auth\Auth;

/**
 * Check for stored user cookies.
 */
class RememberUser
{
    public function handle(): void
    {
        if (! Auth::remember('user')) {
            return;
        }

        $user = Auth::provider('user')->getEntity((string) cookies()->get(Auth::sessionKey('user')));

        if ($user) {
            Auth::setSession($user);
        }
    }
}
