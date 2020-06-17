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

namespace Framework\Support;

use Framework\Http\Request;
use App\Database\Models\UsersModel;

/**
 * Authenticate
 * 
 * Manage user authentication
 */
class Authenticate
{    
    /**
     * authencation attempts count
     *
     * @var int
     */
    public static $attempts = 0;
    
    /**
     * check user credential
     *
     * @param  string $credential
     * @return bool
     */
    public static function check(string $credential = 'email'): bool
    {
        $user = UsersModel::findWhere($credential, '=', Request::getField($credential));

        if (!isset($user->password)) {
            self::$attempts++;
            return false;
        }

        if (!compare_hash(Request::getField('password'), $user->password)) {
            self::$attempts++;
            return false;
        } 
        
        create_session('user', $user);
            
        if (!empty(Request::getField('remember'))) {
            create_cookie('user', Request::getField($credential));
        }

        return true;
    }
    
    /**
     * get user data
     *
     * @return void
     */
    public static function getUser(): string
    {
        return get_session('user');
    }
    
    /**
     * logout user
     *
     * @return void
     */
    public static function logout()
    {
        if (session_has('user')) {
            close_session('user');
        }

        if (cookie_has('user')) {
            delete_cookie('user');
        }
    }
}