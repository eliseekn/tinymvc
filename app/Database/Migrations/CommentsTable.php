<?php

namespace App\Database\Migrations;

use Framework\ORM\Migration;

/**
 * CommentsTable
 * 
 * Migration of comments table
 */
class CommentsTable extends Migration
{    
    /**
     * create table
     *
     * @return void
     */
    public function migrate(): void
    {
        $this->table('comments')
            ->addPrimaryKey('id')
            ->addInteger('post_id')
            ->addString('author')
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
        $this->truncateTable('comments');
    }
    
    /**
     * drop table
     *
     * @return void
     */
    public function delete(): void
    {
        $this->dropTable('comments');
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