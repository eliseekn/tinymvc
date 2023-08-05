<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Database\Seeds;

use App\Database\Factories\UserFactory;

/**
 * Run seeders
 */
class Seeder
{
    public static function run(): void
    {
        (new UserFactory(5))->create();
    }
}
