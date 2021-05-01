<?php

namespace App\Database\Migrations;

use Framework\Database\Migration;

class MessagesTable_20210403034738
{         
    /**
     * name of table
     *
     * @var string
     */
    protected $table = 'messages';

    /**
     * create table
     *
     * @return void
     */
    public function create(): void
    {
        Migration::newTable($this->table)
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
    public function drop(): void
    {
        Migration::dropTable($this->table);
    }
}
