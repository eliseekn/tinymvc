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
use App\Middlewares\DashboardPolicy;
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
        return Session::has('auth_attempts') ? Session::get('auth_attempts') : 0;
    }

    /**
     * make authentication attempt
     *
     * @return mixed
     */
    public static function attempt(Request $request, Users $users, Tokens $tokens)
    {
        //increment authentication attempts
        Session::create('auth_attempts', (self::getAttempts() + 1));

        $user = $users->findSingleByEmail($request->email);

        //check credentials
        if ($user !== false && Encryption::check($request->password, $user->password)) {
            //reset authentication attempts and disable lock
            Session::close('auth_attempts', 'auth_attempts_timeout');

            //check user state
            if (!$user->active) {
                redirect()->back()->withAlert('error', __('user_not_activated', true))->go();
            }

            //check if two factor authentication is enabled
            if ($user->two_steps) {
                $token = random_string(50, true);

                if (AuthLinkMail::send($user->email, $token)) {
                    $tokens->store($request->email, $token, Carbon::now()->addHour()->toDateTimeString());
                    redirect()->back()->withAlert('success', __('confirm_email_link_sent', true))->go();
                } else {
                    redirect()->back()->withAlert('error', __('confirm_email_link_not_sent', true))->go();
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
            (new DashboardPolicy())->handle($request);

            if (Session::has('intended')) {
                $intended = Session::pull('intended');
                redirect()->url($intended)->withToast('success', __('welcome_back') . ' ' . Auth::get('name'))->go();
            }

            if ($user->role === Roles::ROLE[0]) {
                redirect()->route('dashboard.index')->withToast('success', __('welcome') . ' ' . Auth::get('name'))->go();
            }

            redirect()->url()->withToast('success', __('welcome') . ' ' . Auth::get('name'))->go();
        }

        //authentication failed
        Activity::log(__('login_attempts_failed', true), $request->email);

        if (config('auth.max_attempts') > 0 && self::getAttempts() >= config('auth.max_attempts')) {
            redirect()->back()->with('auth_attempts_timeout', Carbon::now()->addMinutes(config('auth.unlock_timeout'))->toDateTimeString())->go();
        } else {
            redirect()->back()->withInputs($request->only('email', 'password'))
                ->withAlert('error', __('login_failed', true))->go();
        }
    }
    
    /**
     * create new user
     *
     * @param  \App\Database\Repositories\Users $u
     * @return bool
     */
    public static function create(Request $request, Users $u): bool
    {
        $users = $u->selectAll(['email', 'phone']);

        foreach ($users as $user) {
            if ($user->email === $request->email || $user->phone === $request->phone) {
                return false;
            }
        }

        $active = empty($users) ? 1 : 0;
        $role = empty($users) ? Roles::ROLE[0] : Roles::ROLE[2];

        $u->store($request, $active, $role);
        return true;
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
     * @param  string $key
     * @return mixed
     */
    public static function get(string $key)
    {
        $data = Session::get('user');
        return $data->$key;
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
        Session::close('user', 'history', 'csrf_token');

        if (self::remember()) {
            Cookies::delete('user');
        }
    }
}
