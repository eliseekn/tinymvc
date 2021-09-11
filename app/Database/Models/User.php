<?php

namespace App\Database\Models;

use Core\Database\Model;

class User extends Model
{
    public static $table = 'users';

    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';

    public function __construct()
    {
        parent::__construct(static::$table);
    }
}
