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
     * return authentication attempts count
     *
     * @return int
     */
    public static function getAttempts(): int
    {
        return get_session('auth_attempts') ?? 0;
    }
    
    /**
     * check user credential
     *
     * @param  string $credential
     * @return bool
     */
    public static function attempt(string $credential = 'email'): bool
    {
        $auth_attempts = get_session('auth_attempts') ?? 0;

        if (!UsersModel::exists($credential, Request::getField($credential))) {
            $auth_attempts++;
            create_session('auth_attempts', $auth_attempts);
            return false;
        }

        $user = UsersModel::findWhere($credential, Request::getField($credential));

        if (!compare_hash(Request::getField('password'), $user->password)) {
            $auth_attempts++;
            create_session('auth_attempts', $auth_attempts);
            return false;
        }

        UsersModel::update($user->id, [
            'online' => 1
        ]);
        
        create_user_session($user);
            
        if (!empty(Request::getField('remember'))) {
            create_user_cookie(Encryption::encrypt(Request::getField($credential)));
        }

        //reset authentication attempts
        close_session('auth_attempts');

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
        if (!UsersModel::exists($credential, Request::getField($credential))) {
            if (!empty($credentials)) {
                foreach ($credentials as $credential) {
                    if (array_key_exists($credential, Request::getField())) {
                        $data[$credential] = Request::getField($credential);
                    }
                }
            
                if (isset($data) && !empty($data)) {
                    UsersModel::insert($data);
                    return true;
                }
            }
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
        return session_has_user();
    }

    /**
     * check is user cookie
     *
     * @return bool
     */
    public static function checkRemember(): bool
    {
        return cookie_has_user();
    }
    
    /**
     * get user data
     *
     * @return mixed
     */
    public static function getUser()
    {
        return get_user_session();
    }
    
    /**
     * logout user
     *
     * @return void
     */
    public static function logout(): void
    {
        if (session_has_user()) {
            UsersModel::update(get_user_session()->id, [
                'online' => 0
            ]);
        
            close_user_session();
        }

        if (cookie_has_user()) {
            delete_user_cookie();
        }
    }
}
