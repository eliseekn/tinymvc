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
use Core\Http\Request;
use Core\Http\Response;

class RedirectIfLogged
{
    public function handle(Request $request, Response $response): void
    {
        if (Auth::check($request)) {
            $response->url(config('security.redirect_if_logged'))->send();
        }
    }
}
