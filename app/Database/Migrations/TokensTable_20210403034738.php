<?php

namespace App\Database\Migrations;

use Framework\Database\Schema;

class TokensTable_20210403034738
{         
    /**
     * name of table
     *
     * @var string
     */
    protected $table = 'tokens';

    /**
     * create table
     *
     * @return void
     */
    public function create(): void
    {
        Schema::createTable($this->table)
            ->addBigInt('id')->primaryKey()
            ->addString('email')->unique()
            ->addString('token')->unique()
            ->addTimestamp('expires')->setNull()
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
