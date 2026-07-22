<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Database\Factories;

use App\Database\Models\RoleModel;
use App\Database\Models\UserModel;
use App\Enums\UserRole;
use Core\Database\Factory\Factory;

class UserFactory extends Factory
{
    public string $model = UserModel::class;

    public function data(): array
    {
        return [
            'name' => faker()->name(),
            'email' => faker()->unique()->safeEmail(),
            'password' => bcrypt('P@ssw0rd'),
            'email_verified_at' => null,
            'role_id' => RoleModel::findByName(UserRole::USER->value)?->getId(),
            'avatar' => null,
        ];
    }
}
