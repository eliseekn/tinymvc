<?php

namespace App\Database\Models;

use Framework\Database\Model;

class FilesModel extends Model
{
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'files';

    /**
     * medias extensions constants
     * 
     * @var array
     */
    public const TYPE = [
        0 => ['jpg', 'jpeg', 'png', 'gif', 'svg'],
        1 => ['mp4', 'flv', 'mpeg', 'webm'],
        2 => ['mp3', 'wav'],
        3 => ['pdf', 'docx', 'doc', 'xls', 'xlsx', 'txt', 'ppt', 'pptx']
    ];
}
