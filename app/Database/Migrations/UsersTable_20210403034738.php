<?php

namespace App\Database\Migrations;

use Framework\Database\Schema;
use App\Database\Repositories\Roles;

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
        Schema::createTable($this->table)
            ->addBigInt('id')->primaryKey()
            ->addBigInt('parent_id')->setNull()
            ->addString('name')
            ->addString('email')->unique()
            ->addString('company')->unique()
            ->addString('phone')->unique()
            ->addString('password')
            ->addString('role')
            ->addString('lang')->default('en')
            ->addString('country')->default('US')
            ->addString('currency')->default('USD')
            ->addString('timezone')->default('UTC')
            ->addBoolean('dark')->default(1)
            ->addBoolean('active')->default(0)
            ->addBoolean('two_steps')->default(0)
            ->addBoolean('alerts')->default(1)
            ->addBoolean('email_notifications')->default(1)
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
