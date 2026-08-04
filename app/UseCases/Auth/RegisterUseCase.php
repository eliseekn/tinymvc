<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\Auth;

use App\Database\Entities\User;
use App\Events\UserRegistered\UserRegisteredEvent;
use App\UseCases\Shared\NotifyUseCase;

final class RegisterUseCase
{
    public function handle(array $data, NotifyUseCase $notifyUseCase): void
    {
        $user = (new User)
            ->fromArray($data)
            ->toModel()
            ->save()
            ?->toEntity(User::class);

        dispatch(new UserRegisteredEvent($user));

        if (config('security.auth.email_verification')) {
            $notifyUseCase->handle($user->getEmail());
        }
    }
}
