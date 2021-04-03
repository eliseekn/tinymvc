<?php

namespace App\Database\Models;

use Framework\Database\Model;

class Tokens
{
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'tokens';

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
     * retrieves token by email
     *
     * @param  string $email
     * @return mixed
     */
    public static function findSingleByEmail(string $email)
    {
        return self::model()->findSingleBy('email', $email);
    }

    /**
     * store token
     *
     * @param  string $email
     * @param  string $token
     * @param  mixed $expires
     * @return int
     */
    public static function store(string $email, string $token, $expires): int
    {
        return self::model()
            ->insert([
                'email' => $email,
                'token' => $token,
                'expires' => $expires
            ]);
    }
    
    /**
     * delete token by email
     *
     * @param  string $email
     * @return mixed
     */
    public static function deleteByEmail(string $email)
    {
        return self::model()->deleteBy('email', $email);
    }
}
