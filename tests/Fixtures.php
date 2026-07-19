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
use App\Database\Models\Role;
use App\Enums\UserRole;
use Core\Database\Entity;

abstract class Fixtures
{
    public static function createAdmin(array $attributes = []): Entity|false|array
    {
        return User::factory()->create(array_merge($attributes, [
            'role_id' => Role::findByName(UserRole::ADMIN->value)?->getId(),
        ]), true);
    }
}
