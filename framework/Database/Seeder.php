<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Database;

/**
 * Manage database seeds
 */
class Seeder
{
    /**
     * insert items in table
     *
     * @param  string $table
     * @param  array $items
     * @return void
     */
    public static function insert(string $table, array $items): void
    {
        Builder::insert($table, $items)->execute();
    }
}
