<?php

namespace App\Database\Migrations;

use Framework\Database\Migration;

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
        Migration::newTable($this->table)
            ->addBigInt('id')->primaryKey()
            ->addBigInt('user_id')
            ->addString('ticket_id')
            ->addString('object')
            ->addBoolean('status')->default(1)
            ->addEnum('priority', ['critical', 'high', 'normal', 'low'])->default('low')
            ->create();
        
        Migration::newTable('ticket_messages')
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
        Migration::dropForeign('ticket_messages', 'ticket');
        Migration::dropTable('ticket_messages');
        Migration::dropTable($this->table);
    }
}
