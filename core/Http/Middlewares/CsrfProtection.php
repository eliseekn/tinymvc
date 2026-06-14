<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http\Middlewares;

use Core\Enums\AppEnv;
use Core\Exceptions\CoreException;
use Exception;

/**
 * CSRF token validator.
 */
class CsrfProtection
{
    /**
     * @throws Exception
     */
    public function handle(): void
    {
        if (config('app.env') === AppEnv::TEST) {
            return;
        }

        if (! request()->inputs()->filled('_csrf_token')) {
            throw new CoreException('Missing csrf token');
        }

        if (! valid_csrf_token(request()->inputs()->get('_csrf_token'))) {
            throw new CoreException('Invalid csrf token ');
        }
    }
}
