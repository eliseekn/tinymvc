<?php

namespace App\Database\Migrations;

use Framework\ORM\Migration;

/**
 * ExampleTable
 */
class ExampleTable
{         
    /**
     * name of table
     *
     * @var string
     */
    protected $table = 'name_of_table';

    /**
     * create table
     *
     * @return void
     */
    public function migrate(): void
    {
        Migration::table($this->table)
            ->addPrimaryKey()
            ->addString('username')
            ->addString('email', false, true)
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
        Migration::truncateTable($this->table);
    }
    
    /**
     * drop table
     *
     * @return void
     */
    public function delete(): void
    {
        Migration::dropTable($this->table);
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
