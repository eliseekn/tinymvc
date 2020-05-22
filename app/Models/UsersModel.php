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
     * get user row
     *
     * @param  string $email email address
     * @return void
     */
    public function get(string $email)
    {
        return $this->findSingle('email', '=', $email);
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
        $user = $this->get($email);
        return is_object($user) ? compare_hash($password, $user->password) : false;
    }
}