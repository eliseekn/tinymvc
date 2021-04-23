<?php

namespace App\Database\Migrations;

use Framework\Database\Schema;

class TicketsTable_20210422132513
{         
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'tickets';

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
            ->addString('ticket_id')
            ->addString('object')
            ->addBoolean('status')->default(1)
            ->addEnum('priority', ['"high"', '"normal"', '"low"'])->default('low')
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
