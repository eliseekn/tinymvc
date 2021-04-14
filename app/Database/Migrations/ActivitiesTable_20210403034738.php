<?php

namespace App\Database\Migrations;

use Framework\Database\Schema;

class ActivitiesTable_20210403034738
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
        Schema::createTable(self::$table)
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
