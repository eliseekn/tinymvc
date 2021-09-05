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
use App\Database\Models\User;
use App\Database\Models\Token;

/**
 * Manage authentications
 */
class Auth
{
    private static function getAttempts()
    {
        return Session::get('auth_attempts', 0);
    }

    public static function attempt(Request $request)
    {
        Session::push('auth_attempts', 1, 0);

        if (self::checkCredentials($request->email, $request->password, $user)) {
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
         
        Alert::default(__('login_failed'))->error();
        redirect()->to('login')->withInputs($request->only('email', 'password'))->withErrors([__('login_failed')])->go();
    }
    
    public static function checkCredentials(string $email, string $password, &$user)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
            $user = User::findBy('email', $email);
            return $user !== false && Encryption::check($password, $user->password);
        }

        $users = User::where('email', 'like', $email)->getAll();

        if (!$users) return false;

        foreach ($users as $u) {
            if (Encryption::check($password, $u->password)) {
                $user = $u;
                return true;
            }
        }

        return false;
    }
    
    public static function checkToken(string $token, &$user)
    {
        $token = Token::findBy('token', $token);
        $user = User::findBy('email', $token->email);

        return $user !== false;
    }
    
    public static function create(Request $request)
    {
        $request->set('password', hash_pwd($request->password));

        return User::create($request->except('csrf_token'));
    }
    
    public static function createToken(string $email): string
    {
        $token = Token::create([
            'email' => $email,
            'token' => generate_token(),
        ]);

        return Encryption::encrypt($token->token);
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

        if (is_null($key)) return $user;

        return $user->{$key};
    }

    public static function forget()
    {
        if (!self::check()) {
            Alert::default(__('not_logged'))->error();
            redirect()->to('login')->go();
        }

        Session::flush('user', 'history', 'csrf_token');

        if (self::remember()) {
            Cookies::delete('user');
        }
    }
    
    public static function forgetAndRedirect(string $redirect = '/')
    {
        self::forget();

        Alert::toast(__('logged_out'))->success();
        redirect()->to($redirect)->go();
    }
    
    public static function redirectIfLogged(string $redirect = '/')
    {
        if (!self::check()) {
            Alert::toast(__('not_logged'))->error();
            redirect()->to('login')->go();
        }

        $url = !Session::has('intended') ? $redirect : Session::pull('intended');

        Alert::toast(__('welcome', ['name' => Auth::get('name')]))->success();
        redirect()->to($url)->go();
    }
}
