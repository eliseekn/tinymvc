<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support;

use Carbon\Carbon;
use Core\Http\Request;
use App\Database\Models\User;
use App\Database\Models\Token;
use Core\Http\Response;

/**
 * Manage authentications
 */
class Auth
{
    public static function getAttempts(): mixed
    {
        return Session::get('auth_attempts', 0);
    }

    public static function attempt(Response $response, Request $request): bool
    {
        Session::push('auth_attempts', 1, 0);

        $credentials = $request->only(['email', 'password']);
        $remember = $request->hasInput('remember');

        if (!self::checkCredentials($credentials['email'], $credentials['password'], $user)) {
            if (config('security.auth.max_attempts') > 0 && Auth::getAttempts() >= config('security.auth.max_attempts')) {
                $response
                    ->back()
                    ->with('auth_attempts_timeout', Carbon::now()->addMinutes(config('security.auth.unlock_timeout'))->toDateTimeString())
                    ->send();
            }

            return false;
        }

        Session::forget(['auth_attempts', 'auth_attempts_timeout']);
        Session::create('user', $user);
            
        if ($remember) {
            Cookies::create('user', $user->attribute('email'), 3600 * 24 * 365);
        }
        
        return true;
    }
    
    public static function checkCredentials(string $email, string $password, &$user): bool
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
            $user = User::findBy('email', $email);
            return $user !== false && Encryption::check($password, $user->attribute('password'));
        }

        $users = User::where('email', 'like', $email)->getAll();

        if (!$users) {
            return false;
        }

        foreach ($users as $u) {
            if (Encryption::check($password, $u->attribute('password'))) {
                $user = $u;
                return true;
            }
        }

        return false;
    }
    
    public static function checkToken(string $token, &$user): bool
    {
        $token = Token::findBy('value', $token);
        $user = User::findBy('email', $token->attribute('email'));
        
        return $user !== false;
    }
    
    public static function createToken(string $email): string
    {
        $token = Token::create([
            'email' => $email,
            'value' => generate_token(),
        ]);

        return Encryption::encrypt($token->attribute('value'));
    }

    public static function check(Request $request): bool
    {
        $result = Session::has('user');

        if (!$result) {
            if (empty($request->getHttpAuth())) {
                return false;
            }

            list($method, $token) = $request->getHttpAuth();
            $result = trim($method) === 'Bearer' && self::checkToken(Encryption::decrypt($token), $user);
        }

        return $result;
    }

    public static function remember(): bool
    {
        return Cookies::has('user');
    }
    
    public static function get(?string $key = null): mixed
    {
        $user = Session::get('user');

        if (is_null($key)) {
            return $user;
        }

        return $user->attribute($key);
    }

    public static function forget(): void
    {
        Session::forget(['user', 'history', 'csrf_token']);

        if (self::remember()) {
            Cookies::delete('user');
        }
    }
}
