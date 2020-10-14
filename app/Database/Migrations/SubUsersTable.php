<?php

namespace App\Database\Migrations;

use Framework\ORM\Migration;

class SubUsersTable
{         
    /**
     * name of table
     *
     * @var string
     */
    protected static $table = 'sub_users';

    /**
     * create table
     *
     * @return void
     */
    public static function migrate(): void
    {
        Migration::table(self::$table)
            ->addBigInt('id')->primaryKey()
            ->addBigInt('parent_id')
            ->addForeignKey('fk_user', 'parent_id')->references('users', 'id')->onDelete()->cascade()->onUpdate()->cascade()
            ->create();
    }
    
    /**
     * drop table
     *
     * @return void
     */
    public static function delete(): void
    {
        Migration::drop(self::$table);
    }
    
    /**
     * reset table
     *
     * @return void
     */
    public static function reset(): void
    {
        self::delete();
        self::migrate();
    }
}
