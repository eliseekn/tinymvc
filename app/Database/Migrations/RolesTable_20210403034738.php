<?php

namespace App\Database\Migrations;

use Framework\Database\Schema;

class RolesTable_20210403034738
{         
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'roles';

    /**
     * create table
     *
     * @return void
     */
    public static function migrate(): void
    {
        Schema::createTable(self::$table)
            ->addInt('id')->primaryKey()
            ->addString('name')->unique()
            ->create();
    }
    
    /**
     * drop table
     *
     * @return void
     */
    public static function delete(): void
    {
        Schema::dropTable(self::$table);
    }
    
    /**
     * refresh table
     *
     * @return void
     */
    public static function refresh(): void
    {
        self::delete();
        self::migrate();
    }
}
