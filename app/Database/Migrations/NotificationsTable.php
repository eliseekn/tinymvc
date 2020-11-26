<?php

namespace App\Database\Migrations;

use Framework\ORM\Migration;

class NotificationsTable
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
        Migration::table(self::$table)
            ->addBigInt('id')->primaryKey()
            ->addText('message')
            ->addString('status')->default('unread')
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
