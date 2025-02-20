<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Auth;

use Core\Enums\HttpMethod;
use Core\Http\Auth;
use Core\Routing\Attributes\Route;
use Core\Routing\Controller;
use Core\Support\Alert;

class LogoutController extends Controller
{
    #[Route(HttpMethod::POST, '/logout', ['auth'])]
    public function __invoke(): void
    {
        Auth::forget();
        Alert::toast(__('logged_out'))->success();

        $this->redirectToUrl(config('app.home'));
    }
}
