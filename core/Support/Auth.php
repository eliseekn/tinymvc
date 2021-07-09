<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support;

use Carbon\Carbon;
use Core\Http\Request;
use Core\Support\Cookies;
use Core\Support\Session;
use Core\Support\Encryption;
use App\Database\Repositories\UserRepository;
use App\Database\Repositories\TokenRepository;

/**
 * Manage authentications
 */
class Auth
{
    private static function getAttempts()
    {
        return Session::get('auth_attempts', 0);
    }

    public static function attempt(Request $request, UserRepository $userRepository)
    {
        Session::put('auth_attempts', 1, 0);

        if (self::checkCredentials($userRepository, $request->email, $request->password, $user)) {
            Session::flush('auth_attempts', 'auth_attempts_timeout');
            Session::create('user', $user);
                
            if ($request->has('remember')) {
                Cookies::create('user', $user->email, 3600 * 24 * 365);
            }
            
            self::redirectIfLogged();   
        }

        if (config('security.auth.max_attempts') > 0 && self::getAttempts() >= config('security.auth.max_attempts')) {
            redirect()->back()->with('auth_attempts_timeout', Carbon::now()->addMinutes(config('security.auth.unlock_timeout'))->toDateTimeString())->go();
        }
            
        redirect()->back()->withInputs($request->only('email', 'password'))->withAlert('error', __('login_failed'))->go();
    }
    
    public static function checkCredentials(UserRepository $userRepository, string $email, string $password, &$user)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
            $user = $userRepository->findByEmail($email);
            return $user !== false && Encryption::check($password, $user->password);
        }

        $users = $userRepository->findAllByEmail($email);

        if (!$users) {
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
    
    public static function checkToken(UserRepository $userRepository, TokenRepository $tokens, string $token, &$user)
    {
        $user = $userRepository->findByToken($tokens, $token);
        return $user !== false;
    }
    
    public static function create(Request $request, UserRepository $userRepository)
    {
        return $userRepository->store($request);
    }
    
    public static function createToken(TokenRepository $tokenRepository, string $email): string
    {
        $token = generate_token();
        $tokenRepository->store($email, $token);
        return Encryption::encrypt($token);
    }

    public static function check()
    {
        return Session::has('user');
    }

    public static function remember()
    {
        return Cookies::has('user');
    }
    
    public static function get(?string $key = null)
    {
        $user = Session::get('user');

        if (is_null($key)) {
            return $user;
        }

        return $user->{$key};
    }

    public static function forget()
    {
        if (!self::check()) {
            redirect()->url('login')->withAlert('error', __('not_logged'))->go();
        }

        Session::flush('user', 'history', 'csrf_token');

        if (self::remember()) {
            Cookies::delete('user');
        }
    }
    
    public static function forgetAndRedirect(string $redirect = '/')
    {
        self::forget();
        redirect()->url($redirect)->withToast('success', __('logged_out'))->go();
    }
    
    public static function redirectIfLogged(string $redirect = '/')
    {
        if (!self::check()) {
           redirect()->url('login')->withAlert('error', __('not_logged'))->go();
        }

        $url = !Session::has('intended') ? $redirect : Session::pull('intended');
        redirect()->url($url)->withToast('success', __('welcome', ['name' => Auth::get('name')]))->go();
    }
}
