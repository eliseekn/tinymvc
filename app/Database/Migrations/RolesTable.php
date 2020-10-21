<?php

namespace App\Database\Migrations;

use Framework\ORM\Migration;

class RolesTable
{         
    /**
     * name of table
     *
     * @var string
     */
    protected static $table = 'roles';

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
