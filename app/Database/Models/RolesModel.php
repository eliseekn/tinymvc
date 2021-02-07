<?php

namespace App\Database\Models;

use Framework\Database\Model;

class RolesModel extends Model
{    
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'roles';

    /**
     * roles constants
     * 
     * @var array
     */
    public const ROLE = [
        0 => 'admin',
        1 => 'customer',
        2 => 'user'
    ];
}
