<?php

namespace App\Database\Migrations;

use Framework\ORM\Migration;

/**
 * ExampleTable
 * 
 */
class ExampleTable extends Migration
{    
    /**
     * __construct
     *
     * @return void
     */
    public function  __construct()
    {
        $this->table = 'name_of_table';
    }

    /**
     * create table
     *
     * @return void
     */
    public function migrate(): void
    {
        $this->table($this->table)
            ->addPrimaryKey('id')
            ->addString('username')
            ->addString('email')
            ->addString('password')
            ->addString('role', 255, false, false, 'user')
            ->addTimestamp('created_at')
            ->create();
    }
    
    /**
     * truncate table
     *
     * @return void
     */
    public function empty(): void
    {
        $this->truncateTable($this->table);
    }
    
    /**
     * drop table
     *
     * @return void
     */
    public function delete(): void
    {
        $this->dropTable($this->table);
    }
    
    /**
     * roll back actions
     *
     * @return void
     */
    public function rollBack(): void
    {
        $this->delete();
        $this->migrate();
    }
}
