<?php

namespace App\Helpers;

use Framework\HTTP\Request;
use Framework\HTTP\Redirect;
use Framework\Support\Encryption;
use App\Database\Models\UsersModel;

class AuthHelper
{
    /**
     * return authentication attempts count
     *
     * @return int
     */
    public static function getAttempts(): int
    {
        return session_has('auth_attempts') ? get_session('auth_attempts') : 0;
    }
    
    /**
     * set authentication attempts count
     *
     * @return void
     */
    private static function setAttempts(): void
    {
        $auth_attempts = self::getAttempts() + 1;
        create_session('auth_attempts', $auth_attempts);
    }

    /**
     * check user credentials
     *
     * @return bool
     */
    private static function checkCredentials($user): bool
    {
        return $user !== false && compare_hash(Request::getField('password'), $user->password);
    }
    
    /**
     * make authentication attempt
     *
     * @return mixed
     */
    public static function authenticate()
    {
        $user = UsersModel::findWhere('email', Request::getField('email'));

        if (!self::checkCredentials($user)) {
            self::setAttempts();

            if (AUTH_ATTEMPTS !== 0 && self::getAttempts() > AUTH_ATTEMPTS) {
                create_session('auth_attempts_timeout', strtotime('+' . AUTH_ATTEMPTS_TIMEOUT . ' minute', strtotime(date('Y-m-d H:i:s'))));
                Redirect::back()->only();
            } else {
                Redirect::back()->withError('Invalid email address and/or password');
            }
        }

        UsersModel::update($user->id, [
            'online' => 1
        ]);
        
        create_user_session($user);
            
        if (!empty(Request::getField('remember'))) {
            create_user_cookie(Encryption::encrypt(Request::getField('email')));
        }

        //reset authentication attempts and disable lock
        close_session('auth_attempts');
        close_session('auth_attempts_timeout');

        return self::getSession();
    }

    /**
     * store new user
     *
     * @return bool
     */
    public static function store(): bool
    {
        if (UsersModel::exists('email', Request::getField('email'))) {
            return false;
        }

        UsersModel::create([
            'name' => Request::getField('name'),
            'email' => Request::getField('email'),
            'password' => hash_string(Request::getField('password'))
        ]);

        return true;
    }

    /**
     * check is user session
     *
     * @return bool
     */
    public static function checkSession(): bool
    {
        return session_has_user();
    }

    /**
     * check is user cookie
     *
     * @return bool
     */
    public static function checkCookie(): bool
    {
        return cookie_has_user();
    }
    
    /**
     * get user data
     *
     * @return mixed
     */
    public static function getSession()
    {
        return get_user_session();
    }
    
    /**
     * forget user authentication
     *
     * @return void
     */
    public static function forget(): void
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
