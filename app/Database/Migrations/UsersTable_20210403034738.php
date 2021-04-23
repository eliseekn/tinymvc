<?php

namespace App\Database\Migrations;

use Framework\Database\Schema;
use App\Database\Repositories\Roles;

class UsersTable_20210403034738
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
        Schema::createTable(self::$table)
            ->addBigInt('id')->primaryKey()
            ->addBigInt('parent_id')->null()
            ->addString('name')
            ->addString('email')->unique()
            ->addString('company')->null()
            ->addString('phone')->unique()
            ->addString('password')
            ->addString('role')->default(Roles::ROLE[1])
            ->addString('lang')->default('en')
            ->addString('country')->default('US')
            ->addString('currency')->default('USD')
            ->addString('timezone')->default('UTC')
            ->addBoolean('dark')->default(1)
            ->addBoolean('active')->default(0)
            ->addBoolean('two_steps')->default(0)
            ->addBoolean('alerts')->default(1)
            ->addBoolean('email_notifications')->default(1)
            ->create();
    }
    
    /**
     * drop table
     *
     * @return void
     */
    public static function delete(): void
    {
        Schema::dropTable(self::$table);
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
