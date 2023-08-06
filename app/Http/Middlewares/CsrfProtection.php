<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Middlewares;

use Core\Http\Request;
use Core\Exceptions\InvalidCsrfTokenException;
use Core\Exceptions\MissingCsrfTokenException;

/**
 * CSRF token validator
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

        if (!$request->filled(['_csrf_token'])) {
            throw new MissingCsrfTokenException();
        }

        if (!valid_csrf_token($request->_csrf_token)) {
            throw new InvalidCsrfTokenException();
        }
    }
}
