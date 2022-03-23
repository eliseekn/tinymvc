<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Database\Seeds;

use App\Database\Factories\UserFactory;
use App\Database\Models\User;

/**
 * Run seeders
 */
class Seeder
{
    public static function run()
    {
        User::factory(UserFactory::class, 5)->create();
    }
}
