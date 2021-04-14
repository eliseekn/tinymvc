<?php

namespace App\Database\Migrations;

use Framework\Database\Schema;

class MediasTable_20210403034738
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
        Schema::createTable(self::$table)
            ->addBigInt('id')->primaryKey()
            ->addString('filename')
            ->addString('title')->null()
            ->addString('description')->null()
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
