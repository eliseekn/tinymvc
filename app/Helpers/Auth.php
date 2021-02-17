<?php

namespace App\Helpers;

use Carbon\Carbon;
use Framework\Http\Request;
use Framework\Http\Redirect;
use Framework\Support\Cookies;
use Framework\Support\Session;
use App\Middlewares\AuthPolicy;
use Framework\Support\Encryption;
use App\Database\Models\RolesModel;
use App\Database\Models\UsersModel;
use App\Database\Models\TokensModel;

class Auth
{    
    /**
     * retrieves authentication attemps count
     *
     * @return int
     */
    private static function getAttempts(): int
    {
        return Session::has('auth_attempts') ? Session::get('auth_attempts') : 0;
    }

    /**
     * make authentication attempt
     *
     * @return mixed
     */
    public static function attempt(Request $request)
    {
        //increment authentication attempts
        Session::create('auth_attempts', (self::getAttempts() + 1));

        $user = UsersModel::findSingleBy('email', $request->email);

        //check credentials
        if ($user !== false && Encryption::compare($request->password, $user->password)) {
            //reset authentication attempts and disable lock
            Session::close('auth_attempts', 'auth_attempts_timeout');

            //check user state
            if (!$user->active) {
                Redirect::back()->withAlert(__('user_not_activated'))->error('');
            }

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
            Session::create('user', $user);
                
            //set user cookie
            if ($request->has('remember')) {
                Cookies::create('user', $request->email, 3600 * 24 * 365);
            }
            
            Activity::log(__('login_attempts_succeeded', true));

            //process to logged user redirection
            AuthPolicy::handle($request);
        }

        //authentication failed
        Activity::log(__('login_attempts_failed', true), $request->email);

        if (config('security.auth.max_attempts') > 0 && self::getAttempts() >= config('security.auth.max_attempts')) {
            Redirect::back()->with('auth_attempts_timeout', Carbon::now()->addMinutes(config('security.auth.unlock_timeout'))->toDateTimeString())->only();
        } else {
            Redirect::back()->withInputs($request->only('email', 'password'))
                ->withAlert(__('login_failed', true))->error('');
        }
    }
    
    /**
     * create new user
     *
     * @return bool
     */
    public static function create(Request $request): bool
    {
        if (
            UsersModel::findMany([
                'email' => $request->email, 
                'phone' => $request->phone
            ])->exists()
        ) {
            return false;
        }
        
        UsersModel::insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company,
            'password' => Encryption::hash($request->password),
            'role' => empty(UsersModel::findAll()) ? RolesModel::ROLE[0] : 'user',
            'active' => empty(UsersModel::findAll()) ? 1 : 0,
        ]);

        return true;
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
     * get user session data
     *
     * @return mixed
     */
    public static function get()
    {
        return Session::get('user');
    }
    
    /**
     * forget user authentication
     *
     * @return void
     */
    public static function forget(): void
    {
        if (self::check()) {
            Activity::log(__('logged_out', true), self::get()->email);
        }

        if (self::check()) {
            Session::close('user', 'history', 'csrf_token');
        }

        if (self::remember()) {
            Cookies::delete('user');
        }
    }
}
