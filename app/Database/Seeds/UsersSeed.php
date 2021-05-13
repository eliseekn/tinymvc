<?php

namespace App\Database\Seeds;

use Framework\Database\QueryBuilder;

class UsersSeed
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
        QueryBuilder::table(self::$table)->insert([
            'name' => 'admin',
            'email' => 'admin@tinymvc.com',
            'password' => pwd_hash('admin'),
            'email_verified' => 1
        ])->execute();
    }
}
