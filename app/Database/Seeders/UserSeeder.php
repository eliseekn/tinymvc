<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Database\Seeders;

use App\Database\Models\User;
use App\Enums\UserRole;

class UserSeeder
{
    public static function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@tiny.mvc',
            'role' => UserRole::ADMIN->value,
            'email_verified_at' => carbon()->toDateTimeString(),
        ]);

        for ($i = 0; $i < 10; ++$i) {
            User::factory()->create([
                'created_at' => carbon(faker()->dateTimeBetween('-24 months'))->toDateTimeString(),
            ]);
        }
    }
}
