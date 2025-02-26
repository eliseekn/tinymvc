<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Middlewares;

use Core\Exceptions\InvalidCsrfTokenException;
use Core\Exceptions\MissingCsrfTokenException;
use Core\Http\Request;
use Exception;

/**
 * CSRF token validator.
 */
class CsrfProtection
{
    /**
     * @throws Exception
     */
    public function handle(Request $request): void
    {
        if (config('app.env') === 'test') {
            return;
        }

        if (! $request->filled('_csrf_token')) {
            throw new MissingCsrfTokenException();
        }

        if (! valid_csrf_token($request->inputs('_csrf_token'))) {
            throw new InvalidCsrfTokenException();
        }
    }
}
