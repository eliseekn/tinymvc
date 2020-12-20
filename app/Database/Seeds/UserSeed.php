<?php

namespace App\Database\Seeds;

use Framework\Database\Seeder;
use Framework\Support\Encryption;

class UserSeed
{     
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'users';

    /**
     * insert row
     *
     * @return void
     */
    public static function insert(): void
    {
        Seeder::add(self::$table, [
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'password' => Encryption::hash('admin'),
            'role' => 'administrator',
            'active' => 1,
            'country' => 209,
            'company' => 'TinyMVC',
            'phone' => '00000000'
        ]);

        Seeder::add(self::$table, [
            'name' => 'customer',
            'email' => 'customer@mail.com',
            'password' => Encryption::hash('customer'),
            'role' => 'customer',
            'active' => 1,
            'country' => 209,
            'company' => 'TinyMVC',
            'phone' => '00000001'
        ]);

        Seeder::add(self::$table, [
            'name' => 'visitor',
            'email' => 'visitor@mail.com',
            'password' => Encryption::hash('visitor'),
            'role' => 'visitor',
            'active' => 1,
            'country' => 209,
            'company' => 'TinyMVC',
            'phone' => '00000002'
        ]);
    }
}
