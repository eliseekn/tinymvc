<?php

namespace App\Database\Seeds;

use Framework\System\Encryption;
use App\Database\Repositories\Roles;
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
            'phone' => '00000000',
            'address' => 'Github',
            'company' => 'TinyMVC',
            'password' => Encryption::hash('admin'),
            'role' => Roles::ROLE[0],
            'status' => 1
        ])->execute();
    }
}
