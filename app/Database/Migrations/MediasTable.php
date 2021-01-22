<?php

namespace App\Database\Migrations;

use Framework\Database\Migration;

class MediasTable
{         
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'medias';

    /**
     * create table
     *
     * @return void
     */
    public static function migrate(): void
    {
        Migration::table(self::$table)
            ->addBigInt('id')->primaryKey()
            ->addString('name')
            ->addString('alt')
            ->addString('url')
            ->addString('title')
            ->addString('caption')
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
