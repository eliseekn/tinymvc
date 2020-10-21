<?php

namespace App\Database\Migrations;

use Framework\ORM\Migration;

class PasswordResetTable
{         
    /**
     * name of table
     *
     * @var string
     */
    protected static $table = 'password_reset';

    /**
     * create table
     *
     * @return void
     */
    public static function migrate(): void
    {
        Migration::table(self::$table)
            ->addBigInt('id')->primaryKey()
            ->addString('email')->unique()
            ->addString('token')
            ->addTimestamp('expires')->null()
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
