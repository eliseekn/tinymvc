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
    public static $table = 'roles';

    /**
     * insert row
     *
     * @return void
     */
    public static function insert(): void
    {
        Seeder::add(self::$table, [
            'title' => 'Administrator',
            'slug' => 'administrator',
            'description' => 'Can access administration dashboard and has all permissions'
        ]);

        Seeder::add(self::$table, [
            'title' => 'Customer',
            'slug' => 'customer',
            'description' => 'Can access administration dashboard'
        ]);

        Seeder::add(self::$table, [
            'title' => 'Visitor',
            'slug' => 'visitor',
            'description' => 'Can only view website pages'
        ]);
    }
}
