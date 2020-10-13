<?php

namespace App\Database\Migrations;

use Framework\ORM\Builder;
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
            ->addString('role')->default('user')
            ->addBoolean('online')->default(0)
            ->addBoolean('active')->default(0)
            ->addTimestamp('created_at')->default(date('Y-m-d H:i:s'))
            ->addTimestamp('updated_at')->default(date('Y-m-d H:i:s'))
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
