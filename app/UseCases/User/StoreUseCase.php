<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\User;

use App\Database\Entities\User;
use App\Database\Models\UserModel;
use App\Events\UserCreated\UserCreatedEvent;
use App\Exceptions\InternalServerException;

final class StoreUseCase
{
    public function handle(array $data): void
    {
        $password = $data['password'];
        $data['password'] = bcrypt($password);

        $user = UserModel::factory()->create($data);

        if (! $user instanceof User) {
            throw new InternalServerException('Failed to create user');
        }

        dispatch(new UserCreatedEvent($user, $password));
    }
}
