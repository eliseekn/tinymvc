<?php

namespace App\Database\Seeds;

use Framework\ORM\Seeder;

class SubUserSeed
{     
    /**
     * name of table
     *
     * @var string
     */
    protected static $table = 'sub_users';

    /**
     * insert row
     *
     * @return void
     */
    public static function insert(): void
    {
        Seeder::add(self::$table, ['parent_id' => 1]);
    }
}
