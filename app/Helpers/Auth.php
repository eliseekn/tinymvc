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

class Auth
{
    /**
     * return authentication attempts count
     *
     * @return int
     */
    private static function getAttempts(): int
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
        Session::create('auth_attempts', self::getAttempts() + 1);
    }
    
    /**
     * make authentication attempt
     *
     * @return mixed
     */
    public static function attempt(Request $request)
    {
        $user = UsersModel::find('email', $request->email)->single();

        //check credentials
        if (!($user !== false && Encryption::compare($request->password, $user->password))) {
            Activity::log('Log in attempts failed', $request->email);
            
            self::setAttempts();

            if (config('security.auth.max_attempts') > 0 && self::getAttempts() > config('security.auth.max_attempts')) {
                Redirect::back()->with('auth_attempts_timeout', Carbon::now()->addMinutes(config('security.auth.unlock_timeout'))->toDateTimeString());
            } else {
                Redirect::back()->withInputs((array) $request->only('email', 'password'))
                    ->withAlert(__('login_failed', true))->error('');
            }
        }

        //reset authentication attempts and disable lock
        Session::close('auth_attempts', 'auth_attempts_timeout');

        //check if two factor authentication is enabled
        if ($user->two_steps) {
            $token = random_string(50, true);

            if (EmailHelper::sendAuth($user->email, $token)) {
                TokensModel::insert([
                    'email' => $request->email,
                    'token' => $token,
                    'expires' => Carbon::now()->addHour()->toDateTimeString()
                ]);

                Redirect::back()->withAlert(__('confirm_email_link_sent', true))->success('');
            } else {
                Redirect::back()->withAlert(__('confirm_email_link_not_sent', true))->error('');
            }
        }

        //set user session
        self::set($user);
            
        //set user cookie
        if ($request->has('remember')) {
            Cookies::create('user', $request->email, 3600 * 24 * 365);
        }
        
        Activity::log('Log in attempts succeeded');
    }
    
    /**
     * create new user
     *
     * @return bool
     */
    public static function create(Request $request): bool
    {
        if (
            UsersModel::select()
                ->where('email', $request->email)
                ->orWhere('phone', $request->phone)
                ->exists()
        ) {
            return false;
        }
        
        UsersModel::insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Encryption::hash($request->password)
        ]);
    }

    /**
     * check is user session
     *
     * @return bool
     */
    public static function check(): bool
    {
        return Session::has('user');
    }

    /**
     * check is user cookie
     *
     * @return bool
     */
    public static function remember(): bool
    {
        return Cookies::has('user');
    }
    
    /**
     * get user data
     *
     * @return mixed
     */
    public static function user()
    {
        return Session::get('user');
    }
    
    /**
     * set user session
     *
     * @param  mixed $user
     * @return void
     */
    public static function set($user): void
    {
        Session::create('user', $user);
    }
    
    /**
     * forget user authentication
     *
     * @return void
     */
    public static function forget(): void
    {
        if (self::check()) {
            Activity::log('Logged out');
        }

        if (self::check()) {
            Session::close('user', 'history', 'csrf_token');
        }

        if (self::remember()) {
            Cookies::delete('user');
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
        return RolesModel::find('slug', $role)->exists() && self::user()->role === $role;
    }
}
