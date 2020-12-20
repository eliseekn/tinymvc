<?php

namespace App\Database\Migrations;

use Framework\Database\Migration;

class UsersTable
{         
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'users';

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
            ->addString('company')->default('')
            ->addString('phone')->unique()
            ->addString('password')
            ->addString('role')->default('visitor')
            ->addString('lang')->default('en')
            ->addSmallInt('country')->default(209)
            ->addString('currency')->default('USD')
            ->addString('timezone')->default('UTC')
            ->addBoolean('dark_theme')->default(1)
            ->addBoolean('active')->default(0)
            ->addBoolean('two_steps')->default(0)
            ->addBoolean('alerts')->default(1)
            ->addBoolean('email_notifications')->default(0)
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
