<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\ORM;

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
    public static function add(string $table, array $items): void
    {
        list($query, $args) = Builder::insert($table, $items)->get();
        Database::getInstance()->executeQuery($query, $args);
    }
}
