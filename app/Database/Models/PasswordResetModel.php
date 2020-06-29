<?php

namespace App\Database\Models;

use Framework\ORM\Model;
use Framework\ORM\Query;

class PasswordResetModel extends Model
{    
    /**
     * name of table
     *
     * @var string
     */
    protected static $table = 'password_reset';
    
    /**
     * check if password reset actin exists
     *
     * @param  string $email
     * @param  string $token
     * @return bool
     */
    public static function exists(string $email, string $token): bool
    {
        return empty(Query::DB()
            ->select('*')
            ->from(static::$table)
            ->whereEquals('email', $email)
            ->and('token', '=', $token)
            ->fetchSingle());
    }
}