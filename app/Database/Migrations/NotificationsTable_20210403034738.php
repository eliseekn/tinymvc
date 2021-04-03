<?php

namespace App\Database\Migrations;

use Framework\Database\Schema;

class NotificationsTable_20210403034738
{         
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'notifications';

    /**
     * create table
     *
     * @return void
     */
    public static function migrate(): void
    {
        Schema::createTable(self::$table)
            ->addBigInt('id')->primaryKey()
            ->addText('message')
            ->addString('status')->default('unread')
            ->addBigInt('user_id')
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
