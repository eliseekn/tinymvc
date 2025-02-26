<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Middlewares;

use Core\Http\Request;
use Core\Http\Response;
use Core\Support\Alert;

/**
 * Check if email has been verified.
 */
class EmailVerified
{
    public function handle(Request $request, Response $response): void
    {
        if (config('security.auth.email_verification') && is_null(auth()->get('email_verified_at'))) {
            Alert::default(__('alert.email_not_verified'))->error();

            $response
                ->url('/login')
                ->intended($request->fullUri())
                ->send();
        }
    }
}
