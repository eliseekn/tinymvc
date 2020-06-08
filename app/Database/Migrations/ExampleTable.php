<?php

namespace App\Database\Migrations;

use Framework\ORM\Migration;

class ExampleTable
{         
    /**
     * name of table
     *
     * @var string
     */
    protected static $table = 'name_of_table';

    /**
     * create table
     *
     * @return void
     */
    public static function migrate(): void
    {
        Migration::table(self::$table)
            ->addPrimaryKey()
            ->addString('email', 255, false, true)
            ->addString('password')
            ->addTimestamp('created_at')
            ->addTimestamp('updated_at')
            ->create();
    }
    
    /**
     * drop table
     *
     * @return void
     */
    public static function delete(): void
    {
        Migration::dropTable(self::$table);
    }
    
    /**
     * roll back actions
     *
     * @return void
     */
    public static function rollBack(): void
    {
        self::delete();
        self::migrate();
    }
}
