<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Database\Seeds;

use App\Database\Factories\UserFactory;
use App\Enums\UserRole;

/**
 * Run seeders
 */
class Seeder
{
    public static function run(): void
    {
        (new UserFactory())->create([
            'email' => 'admin@tiny.mvc',
            'role' => UserRole::ADMIN->value
        ]);

        (new UserFactory(5))->create();
    }
}
