<?php

namespace App\Database\Models;

use Framework\Database\Model;

class MediasModel extends Model
{
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'medias';

    /**
     * medias extensions constants
     * 
     * @var array
     */
    public const FORMATS = [
        0 => ['jpg', 'jpeg', 'png', 'gif'],
        1 => ['mp4', 'flv', 'mpeg', 'webm'],
        2 => ['mp3', 'wav']
    ];
}
