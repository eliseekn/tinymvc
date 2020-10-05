<?php

namespace App\Database\Seeds;

use Framework\ORM\Seeder;

class UserSeed
{     
    /**
     * name of table
     *
     * @var string
     */
    protected static $table = 'users';

    /**
     * insert row
     *
     * @return void
     */
    public static function insert(): void
    {
        Seeder::insert(self::$table, [
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => hash_string('admin'),
            'role' => 'administrator',
            'active' => 1
        ]);
    }
}
