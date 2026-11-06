<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
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
        UserModel::factory(10)->create([
            'created_at' => carbon(faker()->dateTimeBetween('-24 months'))->toDateTimeString(),
            'role_id' => RoleModel::findByName(UserRole::default()->value)?->getId(),
        ], withoutEvent: true);
    }
}
