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
    protected static $table = 'notifications';

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
