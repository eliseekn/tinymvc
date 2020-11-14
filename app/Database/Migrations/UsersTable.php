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
            ->addString('theme')->default('light')
            ->addBoolean('active')->default(0)
            ->addBoolean('two_factor')->default(0)
            ->addBoolean('alerts')->default(1)
            ->addBoolean('notifications_email')->default(0)
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
