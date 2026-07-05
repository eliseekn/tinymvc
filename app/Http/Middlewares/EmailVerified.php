<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Middlewares;

use Core\Enums\Alert\MessageType;
use Core\Http\Response\RedirectResponse;

/**
 * Check if email has been verified.
 */
class EmailVerified
{
    public function handle(): void
    {
        if (config('security.auth.email_verification') && is_null(auth()->getAttributes('email_verified_at'))) {
            new RedirectResponse()
                ->toUrl('/email/notify')
                ->intended(request()->fullUri())
                ->withAlert(MessageType::ERROR, __('alert.email_not_verified'))
                ->send();
        }
    }
}
