<?php

namespace App\Database\Seeds;

use Framework\Database\Seeder;
use Framework\Support\Encryption;
use App\Database\Models\RolesModel;

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
        Seeder::insert(self::$table, [
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'password' => Encryption::hash('admin'),
            'role' => RolesModel::ROLES[0],
            'active' => 1,
            'country' => 209,
            'company' => 'TinyMVC',
            'phone' => '00000000'
        ]);

        Seeder::insert(self::$table, [
            'name' => 'customer',
            'email' => 'customer@mail.com',
            'password' => Encryption::hash('customer'),
            'role' => RolesModel::ROLES[1],
            'active' => 1,
            'country' => 209,
            'company' => 'TinyMVC',
            'phone' => '00000001'
        ]);

        Seeder::insert(self::$table, [
            'name' => 'visitor',
            'email' => 'visitor@mail.com',
            'password' => Encryption::hash('visitor'),
            'role' => RolesModel::ROLES[2],
            'active' => 1,
            'country' => 209,
            'company' => 'TinyMVC',
            'phone' => '00000002'
        ]);
    }
}
