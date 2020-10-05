<?php

namespace App\Database\Seeds;

use Framework\ORM\Seeder;

class RoleSeed
{     
    /**
     * name of table
     *
     * @var string
     */
    protected static $table = 'roles';

    /**
     * insert row
     *
     * @return void
     */
    public static function insert(): void
    {
        Seeder::insert(self::$table, [
            'title' => 'Administrator',
            'slug' => 'administrator',
            'description' => 'Can access administration dashboard and has all permissions'
        ]);

        Seeder::insert(self::$table, [
            'title' => 'Visitor',
            'slug' => 'visitor',
            'description' => 'Can only view website pages'
        ]);
    }
}
