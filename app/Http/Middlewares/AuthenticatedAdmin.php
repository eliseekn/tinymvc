<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Middlewares;

use Core\Enums\Alert\MessageType;
use Core\Http\Auth\Auth;
use Core\Http\Response\RedirectResponse;

/**
 * Check if admin has been authenticated.
 */
class AuthenticatedAdmin
{
    public function handle(): void
    {
        if (! Auth::check('admin')) {
            new RedirectResponse()
                ->toUrl('/admin/login')
                ->intended(request()->fullUri())
                ->withErrors([__('alert.not_logged')])
                ->withAlert(MessageType::ERROR, __('alert.not_logged'))
                ->forgetAuth('admin')
                ->send();
        }
    }
}
