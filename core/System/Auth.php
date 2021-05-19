<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\System;

use Carbon\Carbon;
use Core\Http\Request;
use Core\System\Cookies;
use Core\System\Session;
use Core\System\Encryption;
use App\Database\Repositories\Users;
use App\Database\Repositories\Tokens;

/**
 * Manage authentications
 */
class Auth
{    
    /**
     * retrieves authentication attemps count
     *
     * @return int
     */
    private static function getAttempts(): int
    {
        return Session::get('auth_attempts', 0);
    }

    /**
     * make authentication attempt
     *
     * @param  \Core\Http\Request $request
     * @param  \App\Database\Repositories\Users $users
     * @return void
     */
    public static function attempt(Request $request, Users $users): void
    {
        Session::put('auth_attempts', 1, 0);

        if (self::checkCredentials($users, $request->email, $request->password, $user)) {
            Session::flush('auth_attempts', 'auth_attempts_timeout');
            Session::create('user', $user);
                
            if ($request->has('remember')) {
                Cookies::create('user', $user->email, 3600 * 24 * 365);
            }
            
            self::redirect();   
        }

        if (config('security.auth.max_attempts') > 0 && self::getAttempts() >= config('security.auth.max_attempts')) {
            redirect()->back()->with('auth_attempts_timeout', Carbon::now()->addMinutes(config('security.auth.unlock_timeout'))->toDateTimeString())->go();
        }
            
        redirect()->back()->withInputs($request->only('email', 'password'))->withAlert('error', __('login_failed'))->go();
    }
    
    /**
     * check users credentials
     *
     * @param  \App\Database\Repositories\Users $users
     * @param  string $email
     * @param  string $password
     * @param  mixed $user
     * @return bool
     */
    public static function checkCredentials(Users $users, string $email, string $password, &$user): bool
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
            $user = $users->findOneByEmail($email);
            return $user !== false && Encryption::check($password, $user->password);
        }

        $users = $users->findAllByEmail($email);

        if ($users === false) {
            return false;
        }

        foreach ($users as $u) {
            if (Encryption::check($password, $u->password)) {
                $user = $u;
                return true;
            }
        }

        return false;
    }
    
    /**
     * check users tokens
     *
     * @param  \App\Database\Repositories\Tokens $tokens
     * @param  \App\Database\Repositories\Users $users
     * @param  string $token
     * @param  mixed $user
     * @return bool
     */
    public static function checkToken(Users $users, Tokens $tokens, string $token, &$user): bool
    {
        $user = $users->findOneByToken($tokens, $token);
        return $user !== false;
    }
    
    /**
     * create new user
     *
     * @param  \Core\Http\Request $request
     * @param  \App\Database\Repositories\Users $users
     * @return void
     */
    public static function create(Request $request, Users $users): void
    {
        $users->store($request);
    }
    
    /**
     * create user token
     *
     * @param  \App\Database\Repositories\Tokens $tokens
     * @param  string $email
     * @return string
     */
    public static function createToken(Tokens $tokens, string $email): string
    {
        $token = bin2hex(random_bytes(32));
        $tokens->store($email, $token, true);
        return Encryption::encrypt($token);
    }

    /**
     * check user session
     *
     * @return bool
     */
    public static function check(): bool
    {
        return Session::has('user');
    }

    /**
     * check user cookie
     *
     * @return bool
     */
    public static function remember(): bool
    {
        return Cookies::has('user');
    }
    
    /**
     * get user session data
     *
     * @param  string|null $key
     * @return mixed
     */
    public static function get(?string $key)
    {
        $user = Session::get('user');

        if (is_null($key)) {
            return $user;
        }

        return $user->{$key};
    }
    
    /**
     * forget user authentication
     *
     * @return void
     */
    public static function forget(string $redirect = '/'): void
    {
        if (!self::check()) {
           redirect()->url('login')->withAlert('error', __('not_logged'))->go();
        }

        Session::flush('user', 'history', 'csrf_token');

        if (self::remember()) {
            Cookies::delete('user');
        }

        redirect()->url($redirect)->withToast('success', __('logged_out'))->go();
    }
    
    /**
     * perform redirection of authenticated user
     * 
     * @return void
     */
    public static function redirect()
    {
        if (!self::check()) {
           redirect()->url('login')->withAlert('error', __('not_logged'))->go();
        }

        $url = !Session::has('intended') ? '/' : Session::pull('intended');
        redirect()->url($url)->withToast('success', __('welcome', ['name' => Auth::get('name')]))->go();
    }
}
