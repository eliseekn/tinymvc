<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\Api\v1\User;

use App\Database\Entities\User;
use App\Events\UserCreated\UserCreatedEvent;

final class StoreUseCase
{
    public function handle(array $data): ?User
    {
        $password = $data['password'];

        $user = (new User)
            ->setEmail($data['email'])
            ->setName($data['name'])
            ->setRoleId((int) $data['role_id'])
            ->setPassword($data['password']);

        if ($user->toModel()->save()) {
            dispatch(new UserCreatedEvent($user, $password));

            return $user;
        }

        return null;
    }
}
