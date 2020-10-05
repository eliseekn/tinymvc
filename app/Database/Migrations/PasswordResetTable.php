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
            ->addString('email', 255)->unique()
            ->addString('token')
            ->addDate('expires')->null()
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
