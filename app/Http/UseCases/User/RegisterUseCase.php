<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\UseCases\User;

use App\Database\Models\User;
use App\Events\UserRegistered\UserRegisteredEvent;
use App\Http\UseCases\EmailVerification\NotifyUseCase;
use Core\Enums\HttpCode;
use Core\Support\Alert;
use Core\Support\UseCase;

class RegisterUseCase extends UseCase
{
    public function handle(array $data, NotifyUseCase $notifyUseCase): void
    {
        $data['password'] = hash_pwd($data['password']);

        $user = User::factory()->create($data);

        if (config('security.auth.email_verification')) {
            $notifyUseCase->handle($user->get('email'));
        }

        (new UserRegisteredEvent($user))->dispatch();

        Alert::default(__('account_created'))->success();
        $this->response->url('/login')->send(HttpCode::CREATED);
    }
}
