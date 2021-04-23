<?php

namespace App\Database\Migrations;

use Framework\Database\Schema;

class TicketMessagesTable_20210422132513
{         
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'ticket_messages';

    /**
     * create table
     *
     * @return void
     */
    public static function migrate(): void
    {
        Schema::createTable(self::$table)
            ->addBigInt('id')->primaryKey()
            ->addBigInt('user_id')
            ->addBigInt('ticket_id')
            ->addLongText('message')
            //->addForeignKey('ticket_id')->references('tickets', 'id')->cascade()->onDelete()->onUpdate()
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
