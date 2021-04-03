<?php

namespace App\Database\Migrations;

use Framework\Database\Schema;

class MessagesTable_20210403034738
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
        Schema::createTable(self::$table)
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
