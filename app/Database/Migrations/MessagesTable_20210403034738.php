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
            ->addBoolean('sender_read')->default(0)
            ->addBoolean('recipient_read')->default(0)
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
