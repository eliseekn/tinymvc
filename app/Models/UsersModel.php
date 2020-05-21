<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace App\Models;

use Framework\ORM\Model;

/**
 * UsersModel
 * 
 * Users model class
 */
class UsersModel extends Model
{    
    /**
     * name of table
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * instantiates class
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct($this->table);
    }
    
    /**
     * checks if user is registered
     *
     * @param  string $email email address of user
     * @param  string $password user password
     * @return bool
     */
    public function isRegistered(string $email, string $password): bool
    {
        $user = $this->findWhere('email', '=', $email)->single();
        return empty($user) ? false : compare_hash($user->password, $password);
    }
    
    /**
     * get user role
     *
     * @param  string $email email address of user
     * @return string
     */
    public function getRole(string $email): string
    {
        $user = $this->findWhere('email', '=', $email)->single();
        return $user->role;
    }
}