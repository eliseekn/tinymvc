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
            ->addString('slug', 255, false, true)
            ->addText('description')
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
