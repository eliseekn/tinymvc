<?php

namespace App\Database\Migrations;

use Framework\Database\Migration;

class GalleriesTable
{         
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'galleries';

    /**
     * create table
     *
     * @return void
     */
    public static function migrate(): void
    {
        Migration::table(self::$table)
            ->addBigInt('id')->primaryKey()
            ->addString('title')
            ->addLongText('featured_media')
            ->addLongText('medias')
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
