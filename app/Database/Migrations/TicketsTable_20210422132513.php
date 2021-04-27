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
    protected $table = 'tickets';

    /**
     * create table
     *
     * @return void
     */
    public function create(): void
    {
        Schema::createTable($this->table)
            ->addBigInt('id')->primaryKey()
            ->addBigInt('user_id')
            ->addString('ticket_id')
            ->addString('object')
            ->addBoolean('status')->default(1)
            ->addEnum('priority', ['critical', 'high', 'normal', 'low'])->default('low')
            ->create();
        
        Schema::createTable('ticket_messages')
            ->addBigInt('id')->primaryKey()
            ->addBigInt('user_id')
            ->addBigInt('ticket_id')
            ->addLongText('message')
            ->addForeignKey('ticket_id', 'ticket')->references('tickets', 'id')->onDeleteCascade()->onUpdateCascade()
            ->create();
    }
    
    /**
     * drop table
     *
     * @return void
     */
    public function drop(): void
    {
        Schema::dropForeign('ticket_messages', 'ticket');
        Schema::dropTable('ticket_messages');
        Schema::dropTable($this->table);
    }
}
