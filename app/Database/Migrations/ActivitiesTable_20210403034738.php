<?php

namespace App\Database\Migrations;

use Framework\Database\Migration;

class ActivitiesTable_20210403034738
{         
    /**
     * name of table
     *
     * @var string
     */
    protected $table = 'activities';

    /**
     * create table
     *
     * @return void
     */
    public function create(): void
    {
        Migration::newTable($this->table)
            ->addBigInt('id')->primaryKey()
            ->addString('user')
            ->addString('url')
            ->addString('method')
            ->addString('ip_address')
            ->addString('action')
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
