<?php

namespace App\Database\Migrations;

use Framework\ORM\Migration;

/**
 * PostsTable
 * 
 * Migration of Posts table
 */
class PostsTable extends Migration
{    
    /**
     * create table
     *
     * @return void
     */
    public function migrate(): void
    {
        $this->table('posts')
            ->addPrimaryKey('id')
            ->addString('title')
            ->addString('slug')
            ->addString('image')
            ->addText('content')
            ->addTimestamp('created_at')
            ->addTimestamp('updated_at')
            ->create();
    }
    
    /**
     * truncate table
     *
     * @return void
     */
    public function clear(): void
    {
        $this->truncateTable('posts');
    }
    
    /**
     * drop table
     *
     * @return void
     */
    public function delete(): void
    {
        $this->dropTable('posts');
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