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
     * check if password reset is valid
     *
     * @param  string $email
     * @param  string $token
     * @return mixed
     */
    public static function valid(string $email, string $token)
    {
        return Query::DB()
            ->select('*')
            ->from(static::$table)
            ->whereEquals('email', $email)
            ->and('token', '=', $token)
            ->fetchSingle();
    }
}
