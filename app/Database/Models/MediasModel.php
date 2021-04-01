<?php

namespace App\Database\Models;

use Framework\Database\Model;

class MediasModel
{
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'medias';

    /**
     * create new model instance 
     *
     * @return \Framework\Database\Model
     */
    private static function model(): \Framework\Database\Model
    {
        return new Model(self::$table);
    }

    /**
     * medias extensions constants
     * 
     * @var array
     */
    public const TYPE = [
        0 => ['jpg', 'jpeg', 'png', 'gif', 'svg'],
        1 => ['mp4', 'flv', 'mpeg', 'webm'],
        2 => ['mp3', 'wav']
    ];
}
