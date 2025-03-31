<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Database\Seeders;

use App\Database\Models\Role;
use App\Enums\UserRole;

class RoleSeeder
{
    public static function run(): void
    {
        $roles = [
            UserRole::ADMIN->value,
            UserRole::USER->value,
        ];

        foreach ($roles as $role) {
            Role::factory()->create(['name' => $role]);
        }
    }
}
