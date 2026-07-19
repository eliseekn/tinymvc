<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Database\Seeders;

use App\Database\Models\RoleModel;
use App\Database\Models\UserModel;
use App\Enums\UserRole;

class UserSeeder
{
    public static function run(): void
    {
        UserModel::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@tiny.mvc',
            'role_id' => RoleModel::findByName(UserRole::ADMIN->value)?->getId(),
            'email_verified_at' => carbon()->toDateTimeString(),
        ], true);

        UserModel::factory(10)->create([
            'created_at' => carbon(faker()->dateTimeBetween('-24 months'))->toDateTimeString(),
        ], true);
    }
}
