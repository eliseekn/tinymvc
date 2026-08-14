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
use App\Database\Models\RoleModel;
use App\Enums\UserRole;
use App\Events\UserRegistered\UserRegisteredEvent;
use App\Exceptions\InternalServerException;
use App\UseCases\Shared\NotifyUseCase;

final class RegisterUseCase
{
    public function handle(array $data, NotifyUseCase $notifyUseCase): void
    {
        $user = (new User)
            ->setEmail($data['email'])
            ->setName($data['name'])
            ->setRoleId((int) RoleModel::findByName(UserRole::USER->value)->getId())
            ->setPassword($data['password']);

        if (! $user->toModel()->save()) {
            throw new InternalServerException('Failed to create user');
        }

        dispatch(new UserRegisteredEvent($user));

        if (config('security.auth.email_verification')) {
            $notifyUseCase->handle($user->getEmail());
        }
    }
}
