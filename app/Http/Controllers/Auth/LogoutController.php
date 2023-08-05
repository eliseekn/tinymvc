<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Auth;

use Core\Support\Auth;
use Core\Support\Alert;
use Core\Http\Response;

class LogoutController
{
	public function __invoke(Response $response): void
	{
        Auth::forget();
        Alert::toast(__('logged_out'))->success();

        $response->url(config('app.home'))->send(302);
	}
}
