<?php

namespace App\Database\Migrations;

use Framework\ORM\Migration;

class UsersTable
{         
    /**
     * name of table
     *
     * @var string
     */
    protected static $table = 'users';

    /**
     * create table
     *
     * @return void
     */
    public static function migrate(): void
    {
        Migration::table(self::$table)
            ->addPrimaryKey()
            ->addString('name')
            ->addString('email', 255, false, true)
            ->addString('password')
            ->addString('role', 255, false, false, 'user')
            ->addBoolean('online', false, 0)
            ->addTimestamp('created_at')
            ->addTimestamp('updated_at')
            ->create();
    }
    
    /**
     * drop table
     *
     * @return void
     */
    public static function delete(): void
    {
        Migration::dropTable(self::$table);
    }
    
    /**
     * roll back actions
     *
     * @return void
     */
    public static function rollBack(): void
    {
        self::delete();
        self::migrate();
    }
}
