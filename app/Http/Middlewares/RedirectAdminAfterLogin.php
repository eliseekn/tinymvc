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
use Core\Http\Response\RedirectResponse;

class RedirectAdminAfterLogin
{
    public function handle(): void
    {
        if (Auth::check('admin')) {
            new RedirectResponse()
                ->toUrl(config('security.auth.providers.admin.redirect_after_login'))
                ->send();
        }
    }
}
