<?php

namespace App\Database\Models;

use Core\Database\Model;

class User extends Model
{
    public static $table = 'users';

    public function __construct()
    {
        parent::__construct(static::$table);
    }
}
