<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests;

use App\Database\Entities\User;
use App\Database\Models\RoleModel;
use App\Database\Models\UserModel;
use App\Enums\UserRole;

abstract class Fixtures
{
    public static function createAdmin(array $attributes = []): ?User
    {
        return UserModel::factory()->create(array_merge($attributes, [
            'role_id' => RoleModel::findByName(UserRole::ADMIN->value)?->getId(),
        ]), true)?->toEntity(User::class);
    }
}
