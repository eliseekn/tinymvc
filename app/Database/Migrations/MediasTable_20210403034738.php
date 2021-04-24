<?php

namespace App\Database\Migrations;

use Framework\Database\Schema;

class MediasTable_20210403034738
{         
    /**
     * name of table
     *
     * @var string
     */
    protected $table = 'medias';

    /**
     * create table
     *
     * @return void
     */
    public function create(): void
    {
        Schema::createTable($this->table)
            ->addBigInt('id')->primaryKey()
            ->addString('filename')
            ->addString('title')->setNull()
            ->addString('description')->setNull()
            ->addString('url')
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
