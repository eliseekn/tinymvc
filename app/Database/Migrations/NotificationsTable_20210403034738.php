<?php

namespace App\Database\Migrations;

use Framework\Database\Migration;

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
        Migration::newTable($this->table)
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
        Migration::dropTable($this->table);
    }
}
