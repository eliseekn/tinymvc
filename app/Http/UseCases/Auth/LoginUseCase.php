<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\UseCases\Auth;

use App\Database\Models\User;
use Core\Http\Auth;
use Core\Support\Alert;

final class LoginUseCase
{
    public function handle(array $data): void
    {
        $user = User::findByEmail($data['email']);

        if (Auth::attempt($user)) {
            Alert::toast(__('alert.welcome', ['name' => $user->get('name')]))->success();
            response()->url('/dashboard')->send();
        }

        Alert::default(__('alert.login_failed'))->error();

        response()
            ->url('/login')
            ->withInputs($data)
            ->withErrors([__('alert.login_failed')])
            ->send();
    }
}
