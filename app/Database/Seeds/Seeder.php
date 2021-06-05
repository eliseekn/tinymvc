<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Database\Seeds;

/**
 * Manage database seeds
 */
class Seeder
{
    /**
     * insert seeds
     *
     * @return void
     */
    public static function run(): void
    {
        UsersSeed::insert();
    }
}
