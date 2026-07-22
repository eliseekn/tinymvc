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
use App\Database\Models\UserModel;

final class StoreUseCase
{
    public function handle(array $data): ?User
    {
        $data['password'] = bcrypt($data['password']);

        return UserModel::factory()->create($data)->toEntity(User::class);
    }
}
