<?php

namespace App\Database\Models;

use Core\Database\Model;

class Token extends Model
{
    public static $table = 'tokens';

    public function __construct()
    {
        parent::__construct(static::$table);
    }
}
