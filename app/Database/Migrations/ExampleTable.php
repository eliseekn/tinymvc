<?php

namespace App\Database\Migrations;

use Framework\ORM\Migration;

/**
 * ExampleTable
 * 
 * Migration of example table
 */
class UsersTable extends Migration
{    
    /**
     * create table
     *
     * @return void
     */
    public function migrate(): void
    {
        $this->table('name_of_table')
            ->addPrimaryKey('id')
            ->addString('username')
            ->addString('email')
            ->addString('password')
            ->addString('role')
            ->addTimestamp('created_at')
            ->create();
    }
    
    /**
     * truncate table
     *
     * @return void
     */
    public function clear(): void
    {
        $this->truncateTable('name_of_table');
    }
    
    /**
     * drop table
     *
     * @return void
     */
    public function delete(): void
    {
        $this->dropTable('name_of_table');
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