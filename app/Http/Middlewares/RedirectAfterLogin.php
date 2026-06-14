<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Middlewares;

use Core\Http\Auth;
use Core\Http\Response\RedirectResponse;

class RedirectAfterLogin
{
    public function handle(): void
    {
        if (Auth::check()) {
            new RedirectResponse()
                ->toUrl(config('security.auth.redirect_after_login'))
                ->send();
        }
    }
}
