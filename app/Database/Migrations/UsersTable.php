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
            ->addBigInt('id')->primaryKey()
            ->addString('name')
            ->addString('email')->unique()
            ->addString('password')
            ->addString('role')->default('visitor')
            ->addBoolean('online')->default(0)
            ->addBoolean('active')->default(0)
            ->addTimestamp('created_at')->default(date('Y-m-d H:i:s'))
            ->addTimestamp('updated_at')->default(date('Y-m-d H:i:s'))
            ->create();

        Migration::table('sub_users')
            ->addBigInt('id')->primaryKey()
            ->addBigInt('parent_id')
            ->addForeignKey('fk_users', 'parent_id')->references('users', 'id')->onDelete()->cascade()->onUpdate()->cascade()
            ->create();
    }
    
    /**
     * drop table
     *
     * @return void
     */
    public static function delete(): void
    {
        Migration::drop('sub_users');
        Migration::drop(self::$table);
    }
    
    /**
     * refresh table
     *
     * @return void
     */
    public static function refresh(): void
    {
        self::delete();
        self::migrate();
    }
}
