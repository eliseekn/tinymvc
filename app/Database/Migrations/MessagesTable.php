<?php

namespace App\Database\Migrations;

use Framework\Database\Migration;

class MessagesTable
{         
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'messages';

    /**
     * create table
     *
     * @return void
     */
    public static function migrate(): void
    {
        Migration::table(self::$table)
            ->addBigInt('id')->primaryKey()
            ->addBigInt('sender')
            ->addBigInt('recipient')
            ->addText('message')
            ->addString('sender_status')->default('unread')
            ->addString('recipient_status')->default('unread')
            ->addBoolean('sender_deleted')->default(0)
            ->addBoolean('recipient_deleted')->default(0)
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
