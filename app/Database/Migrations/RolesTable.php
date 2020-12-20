<?php

namespace App\Database\Migrations;

use Framework\Database\Migration;

class RolesTable
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
        Migration::table(self::$table)
            ->addInt('id')->primaryKey()
            ->addString('title')
            ->addString('slug')->unique()
            ->addText('description')
            ->create();
    }
    
    /**
     * drop table
     *
     * @return void
     */
    public static function delete(): void
    {
        Migration::drop(self::$table);
    }
    
    /**
     * reset table
     *
     * @return void
     */
    public static function reset(): void
    {
        self::delete();
        self::migrate();
    }
}
