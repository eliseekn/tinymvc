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
            ->addString('filename')
            ->addString('title')->default('')
            ->addString('description')->default('')
            ->addString('url')
            ->addBigInt('user_id')
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
