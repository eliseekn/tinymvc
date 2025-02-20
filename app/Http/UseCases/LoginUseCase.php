<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\UseCases;

use Core\Http\Auth;
use Core\Support\Alert;
use Core\Support\UseCase;

class LoginUseCase extends UseCase
{
    public function handle(array $data): void
    {
        if (Auth::attempt($this->response, $this->request)) {
            Alert::toast(__('welcome', ['name' => Auth::get('name')]))->success();
            $this->response->url('/dashboard')->send();
        }

        Alert::default(__('login_failed'))->error();

        $this->response
            ->url('/login')
            ->withInputs($data)
            ->withErrors([__('login_failed')])
            ->send();
    }
}
