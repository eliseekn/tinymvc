<?php

namespace App\Helpers;

use Carbon\Carbon;
use Framework\HTTP\Request;
use Framework\HTTP\Redirect;
use Framework\Support\Cookies;
use Framework\Support\Session;
use Framework\Support\Encryption;
use App\Database\Models\RolesModel;
use App\Database\Models\UsersModel;
use App\Database\Models\TokensModel;

class AuthHelper
{
    /**
     * return authentication attempts count
     *
     * @return int
     */
    public static function getAttempts(): int
    {
        return Session::has('auth_attempts') ? Session::get('auth_attempts') : 0;
    }
    
    /**
     * set authentication attempts count
     *
     * @return void
     */
    private static function setAttempts(): void
    {
        $auth_attempts = self::getAttempts() + 1;
        Session::create('auth_attempts', $auth_attempts);
    }
    
    /**
     * make authentication attempt
     *
     * @return mixed
     */
    public static function authenticate()
    {
        $user = UsersModel::find('email', Request::getField('email'))->single();

        if (!($user !== false && Encryption::verify(Request::getField('password'), $user->password))) {
            self::setAttempts();

            if (config('security.auth.max_attempts') > 0 && self::getAttempts() > config('security.auth.max_attempts')) {
                Session::create('auth_attempts_timeout', Carbon::now()->addMinutes(config('security.auth.unlock_timeout'))->format('Y-m-d H:i:s'));
                Redirect::back()->only();
            } else {
                Redirect::back()->withError('Invalid email address or password');
            }
        }

        if ($user->two_factor) {
            $token = random_string(50, true);

            if (EmailHelper::sendAuthentication($user->email, $token)) {
                TokensModel::insert([
                    'email' => Request::getField('email'),
                    'token' => $token,
                    'expires' => Carbon::now()->addHour()->format('Y-m-d H:i:s')
                ]);

                Redirect::back()->withInfo('Please check your email account to confirm your email address.');
            } else {
                Redirect::back()->withInfo('An unexpected error occurs while . Please contact the adminstrator for more infos');
            }
        }

        UsersModel::update(['online' => 1])->where('id', $user->id)->persist();
        Session::setUser($user);
            
        if (!empty(Request::getField('remember'))) {
            Cookies::setUser(Encryption::encrypt(Request::getField('email')));
        }

        //reset authentication attempts and disable lock
        Session::close('auth_attempts');
        Session::close('auth_attempts_timeout');
    }
    
    /**
     * email authentication
     *
     * @param  string $email
     * @return void
     */
    public static function authEmail(string $email): void
    {
        $user = UsersModel::find('email', Request::getField('email'))->single();
        
        UsersModel::update(['online' => 1])->where('id', $user->id)->persist();
        Session::setUser($user);
            
        if (!empty(Request::getField('remember'))) {
            Cookies::setUser(Encryption::encrypt(Request::getField('email')));
        }
    }

    /**
     * store new user
     *
     * @return bool
     */
    public static function store(): bool
    {
        if (UsersModel::find('email', Request::getField('email'))->exists()) {
            return false;
        }
        
        UsersModel::insert([
            'name' => Request::getField('name'),
            'email' => Request::getField('email'),
            'password' => Encryption::hash(Request::getField('password'))
        ]);
    }

    /**
     * check is user session
     *
     * @return bool
     */
    public static function checkSession(): bool
    {
        return Session::hasUser();
    }

    /**
     * check is user cookie
     *
     * @return bool
     */
    public static function checkCookie(): bool
    {
        return Cookies::hasUser();
    }
    
    /**
     * get user data
     *
     * @return mixed
     */
    public static function getSession()
    {
        return Session::getUser();
    }
    
    /**
     * forget user authentication
     *
     * @return void
     */
    public static function forget(): void
    {
        if (self::checkSession()) {
            UsersModel::update(['online' => 0])->where('id', self::getSession()->id)->persist();        
            Session::deleteUser();
        }

        if (self::checkCookie()) {
            Cookies::deleteUser();
        }
    }
    
    /**
     * check user role
     *
     * @param  string $role
     * @return bool
     */
    public static function hasRole(string $role): bool
    {
        return RolesModel::find('slug', $role)->exists() && self::getSession()->role === $role;
    }
}
