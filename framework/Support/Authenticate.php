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

use Framework\HTTP\Request;
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
    public static function attempt(string $credential = 'email'): bool
    {
        if (!UsersModel::exists($credential, Request::getField($credential))) {
            self::$attempts++;
            return false;
        }

        $user = UsersModel::findWhere($credential, Request::getField($credential));

        if (!compare_hash(Request::getField('password'), $user->password)) {
            self::$attempts++;
            return false;
        }

        UsersModel::update($user->id, [
            'online' => 1
        ]);
        
        create_session('user', $user);
            
        if (!empty(Request::getField('remember'))) {
            $credential = Encryption::encrypt(Request::getField($credential));
            create_cookie('user', $credential, 3600 * 24 * 30);
        }

        //reset attempts
        self::$attempts = 0;

        return true;
    }

    /**
     * authenticate new user
     *
     * @param  array $credentials
     * @param  string $credential
     * @return bool
     */
    public static function new(array $credentials, string $credential = 'email'): bool
    {
        if (UsersModel::exists($credential, Request::getField($credential))) {
            return false;
        }

        foreach ($credentials as $credential) {
            if (array_key_exists($credential, Request::getField())) {
                $data[$credential] = Request::getField($credential);
            }
        }

        if (isset($data)) {
            UsersModel::insert($data);
            return true;
        }

        return false;
    }

    /**
     * check is user session
     *
     * @return bool
     */
    public static function check(): bool
    {
        return session_has('user');
    }

    /**
     * check is user cookie
     *
     * @return bool
     */
    public static function checkRemember(): bool
    {
        return cookie_has('user');
    }
    
    /**
     * get user data
     *
     * @return mixed
     */
    public static function getUser()
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