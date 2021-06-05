<?php

namespace App\Database\Migrations;

use Core\Database\Migration;

class UsersTable_20210403034738
{         
    /**
     * name of table
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * create table
     *
     * @return void
     */
    public function create(): void
    {
        Migration::table($this->table)
            ->addPrimaryKey('id')
            ->addString('name')
            ->addString('email')->unique()
            ->addBoolean('email_verified')->default(0)
            ->addString('password')
            ->migrate();
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
