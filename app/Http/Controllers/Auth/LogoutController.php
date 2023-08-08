<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Core\Support\Auth;
use Core\Support\Alert;

class LogoutController extends Controller
{
	public function __invoke(): void
	{
        Auth::forget();
        Alert::toast(__('logged_out'))->success();
        $this->redirect(config('app.home'));
	}
}
