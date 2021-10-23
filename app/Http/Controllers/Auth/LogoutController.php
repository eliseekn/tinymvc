<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Auth;

use Core\Support\Auth;
use Core\Support\Alert;
use Core\Http\Response\Response;

class LogoutController
{
	public function __invoke(Response $response)
	{
        Auth::forget();

        Alert::toast(__('logged_out'))->success();
        $response->redirect()->to(Auth::HOME)->go();
	}
}
