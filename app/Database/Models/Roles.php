<?php

namespace App\Database\Models;

use Framework\Database\Model;

class Roles
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

    /**
     * create new model instance 
     *
     * @return \Framework\Database\Model
     */
    private static function model(): \Framework\Database\Model
    {
        return new Model(self::$table);
    }
}
