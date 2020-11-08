<?php

namespace App\Database\Seeds;

use Framework\ORM\Seeder;
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
            'phone' => 00000000
        ]);
    }
}
