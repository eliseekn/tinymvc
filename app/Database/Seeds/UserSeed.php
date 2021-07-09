<?php

namespace App\Database\Seeds;

use Core\Database\QueryBuilder;

class UserSeed
{     
    public static $table = 'users';

    public static function insert()
    {
        QueryBuilder::table(self::$table)->insert([
            'name' => 'admin',
            'email' => 'admin@tinymvc.dev',
            'password' => hash_pwd('admin'),
            'verified' => 1
        ])->execute();
    }
}
