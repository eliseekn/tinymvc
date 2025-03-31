<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\UseCases\User;

use App\Database\Models\User;
use App\Events\UserRegistered\UserRegisteredEvent;
use App\Http\UseCases\EmailVerification\NotifyUseCase;
use Core\Support\Alert;
use Core\Support\UseCase;

final class RegisterUseCase extends UseCase
{
    public function handle(array $data, NotifyUseCase $notifyUseCase): void
    {
        $data['password'] = bcrypt($data['password']);

        $user = User::factory()->create($data);

        if (config('security.auth.email_verification')) {
            $notifyUseCase->handle($user->get('email'));
        }

        dispatch(new UserRegisteredEvent($user));

        Alert::default(__('alert.account_created'))->success();
        $this->response->view('auth.login')->send();
    }
}
