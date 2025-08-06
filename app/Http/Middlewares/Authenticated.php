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
use Core\Support\Alert;

/**
 * Check if user has been authenticated.
 */
class Authenticated
{
    public function handle(): void
    {
        if (! Auth::check()) {
            Auth::forget();
            Alert::default(__('alert.not_logged'))->error();

            response()
                ->url('/login')
                ->intended(request()->fullUri())
                ->withErrors([__('alert.not_logged')])
                ->send();
        }
    }
}
