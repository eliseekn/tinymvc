<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Mails\AuthLinkMail;
use Framework\Http\Request;
use Framework\System\Cookies;
use Framework\System\Session;
use Framework\System\Encryption;
use App\Database\Repositories\Roles;
use App\Database\Repositories\Users;
use App\Database\Repositories\Tokens;

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
     * @param  \Framework\Http\Request $request
     * @param  \App\Database\Repositories\Users $users
     * @param  \App\Database\Repositories\Tokens $tokens
     * @return void
     */
    public static function attempt(Request $request, Users $users, Tokens $tokens): void
    {
        //increment authentication attempts
        Session::put('auth_attempts', 1, 0);

        //check credentials
        if (self::checkByCredentials($users, $request->email, $request->password, $user)) {
            //reset authentication attempts and disable lock
            Session::flush('auth_attempts', 'auth_attempts_timeout');

            //check user state
            if (!$user->status) {
                redirect()->back()->withAlert('error', __('user_not_activated', true))->go();
            }

            //check if two factor authentication is enabled
            if ($user->two_steps) {
                $token = random_string(50, true);

                if (AuthLinkMail::send($user->email, $token)) {
                    $tokens->store($user->email, $token, Carbon::now()->addHour()->toDateTimeString());
                    redirect()->back()->withAlert('success', __('confirm_email_link_sent', true))->go();
                } else {
                    redirect()->back()->withAlert('error', __('confirm_email_link_not_sent', true))->go();
                }
            }

            //set user session
            Session::create('user', $user);
                
            //set user cookie
            if ($request->has('remember')) {
                Cookies::create('user', $user->email, 3600 * 24 * 365);
            }
            
            Activity::log(__('login_attempts_succeeded', true));

            //redirect authenticated user
            self::redirect();   
        }

        //authentication failed
        Activity::log(__('login_attempts_failed', true), $request->email);

        if (config('security.auth.max_attempts') > 0 && self::getAttempts() >= config('security.auth.max_attempts')) {
            redirect()->back()->with('auth_attempts_timeout', Carbon::now()->addMinutes(config('security.auth.unlock_timeout'))->toDateTimeString())->go();
        } else {
            redirect()->back()->withInputs($request->only('email', 'password'))
                ->withAlert('error', __('login_failed', true))->go();
        }
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
    public static function checkByCredentials(Users $users, string $email, string $password, &$user): bool
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
            $user = $users->findOneByEmail($email);
            return $user !== false && Encryption::check($password, $user->password);
        }

        $users = $users->findManyByUsername($email);

        if ($users !== false) {
            foreach ($users as $u) {
                if (Encryption::check($password, $u->password)) {
                    $user = $u;
                    return true;
                }
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
    public static function checkByToken(Users $users, Tokens $tokens, string $token, &$user): bool
    {
        $user = $tokens->findOneByToken($users, $token);
        return $user !== false;
    }
    
    /**
     * create new user
     *
     * @param  \Framework\Http\Request $request
     * @param  \App\Database\Repositories\Users $users
     * @return void
     */
    public static function create(Request $request, Users $users): void
    {
        $users->register($request);
        Activity::log(__('user_registered', true), $request->email);
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
        $tokens->store($email, $token);
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
    public static function get(?string $key = null)
    {
        $data = Session::get('user');

        if (is_null($key)) {
            return $data;
        }

        return $data->$key;
    }
    
    /**
     * check user role
     *
     * @param  string $role
     * @return bool
     */
    public static function role(string $role): bool
    {
        return self::get('role') === $role;
    }
    
    /**
     * forget user authentication
     *
     * @return void
     */
    public static function forget(): void
    {
        if (!self::check()) {
           return; 
        }

        Activity::log(__('logged_out', true), self::get('email'));
        Session::flush('user', 'history', 'csrf_token');

        if (self::remember()) {
            Cookies::delete('user');
        }
    }

    /**
     * perform redirection of authenticated user
     * 
     * @return void
     */
    public static function redirect()
    {
        if (!self::check()) {
           return; 
        }

        if (self::get('role') === Roles::ROLE[3]) {
            if (Session::has('intended')) {
                redirect()->url(Session::pull('intended'))->withToast('success', __('welcome_back') . ' ' . Auth::get('name'))->go();
            }
            
            redirect()->url('/')->withToast('success', __('welcome') . ' ' . Auth::get('name'))->go();
        }

        if (Session::has('intended')) {
            redirect()->url(Session::pull('intended'))->withToast('success', __('welcome_back') . ' ' . Auth::get('name'))->go();
        }

        redirect()->route('dashboard.index')->withToast('success', __('welcome') . ' ' . Auth::get('name'))->go();
    }
}
