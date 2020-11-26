<?php

namespace App\Database\Migrations;

use Framework\ORM\Migration;

class ActivitiesTable
{         
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'activities';

    /**
     * create table
     *
     * @return void
     */
    public static function migrate(): void
    {
        Migration::table(self::$table)
            ->addBigInt('id')->primaryKey()
            ->addString('user')
            ->addString('url')
            ->addString('method')
            ->addString('ip_address')
            ->addString('action')
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
