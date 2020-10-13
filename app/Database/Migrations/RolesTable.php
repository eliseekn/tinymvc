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
            ->addTimestamp('created_at')->default(date('Y-m-d H:i:s'))
            ->addTimestamp('updated_at')->default(date('Y-m-d H:i:s'))
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
