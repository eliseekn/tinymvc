<?php

namespace App\Database\Seeds;

use Framework\Database\Seeder;

class RoleSeed
{     
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'roles';

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
            'title' => 'Customer',
            'slug' => 'customer',
            'description' => 'Can access administration dashboard'
        ]);

        Seeder::insert(self::$table, [
            'title' => 'Visitor',
            'slug' => 'visitor',
            'description' => 'Can only view website pages'
        ]);
    }
}
