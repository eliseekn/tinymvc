<?php

namespace App\Database\Migrations;

use Framework\Database\Schema;

class NotificationsTable_20210403034738
{         
    /**
     * name of table
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * create table
     *
     * @return void
     */
    public function create(): void
    {
        Schema::createTable($this->table)
            ->addBigInt('id')->primaryKey()
            ->addText('message')
            ->addBoolean('status')->default(0)
            ->addBigInt('user_id')
            ->create();
    }
    
    /**
     * drop table
     *
     * @return void
     */
    public function drop(): void
    {
        Schema::dropTable($this->table);
    }
}
