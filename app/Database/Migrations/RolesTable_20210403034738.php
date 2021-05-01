<?php

namespace App\Database\Migrations;

use Framework\Database\Migration;

class RolesTable_20210403034738
{         
    /**
     * name of table
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * create table
     *
     * @return void
     */
    public function create(): void
    {
        Migration::newTable($this->table)
            ->addInt('id')->primaryKey()
            ->addString('name')->unique()
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
