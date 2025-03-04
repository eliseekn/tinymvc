<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http;

use App\Database\Models\Token;
use App\Database\Models\User;
use Core\Database\Model;
use Core\Enums\HttpAuthMethod;
use Core\Support\Encryption;

/**
 * Manage authentications.
 */
class Auth
{
    public static function getAttempts(): mixed
    {
        return session()->get('auth_attempts', 0);
    }

    public static function attempt(Response $response, Request $request, &$user): bool
    {
        session()->push('auth_attempts', 1, 0);
        $credentials = $request->only([config('security.auth.identifier'), 'password']);

        if (! self::checkCredentials($credentials[config('security.auth.identifier')], $credentials['password'], $user)) {
            if (config('security.auth.max_attempts') > 0 && self::getAttempts() >= config('security.auth.max_attempts')) {
                $response
                    ->back()
                    ->with('auth_attempts_timeout', carbon()->addMinutes(config('security.auth.unlock_timeout'))->toDateTimeString())
                    ->send();
            }

            return false;
        }

        session()->forget(['auth_attempts', 'auth_attempts_timeout']);
        session()->regenerate();
        session()->create('user', $user->get());

        if ($request->hasInput('remember')) {
            cookies()->create('user', $user->get('email'), 3600 * 24 * 365);
        }

        return true;
    }

    public static function checkCredentials(string $identifier, string $password, &$user): bool
    {
        $user = User::findByIdentifier($identifier);

        return $user !== false && Encryption::check($password, $user->get('password'));
    }

    public static function checkToken(string $token, &$user): bool
    {
        $token = Token::findByValue($token);

        if ($token === false) {
            $user = null;
            return false;
        }

        $user = User::findByEmail($token->get('email'));

        return $user !== false;
    }

    public static function createToken(string $email): string
    {
        $token = Token::factory()->create([
            'email' => $email,
            'value' => generate_token(),
        ]);

        return encrypt($token->get('value'));
    }

    public static function deleteToken(Request $request): bool
    {
        $token = Token::findByValue(self::getToken($request));

        return $token && $token->delete();
    }

    public static function getToken(Request $request): string
    {
        if (empty($request->getHttpAuth())) {
            return '';
        }

        list($method, $token) = $request->getHttpAuth();

        return trim($method) !== HttpAuthMethod::BEARER ? '' : decrypt($token);
    }

    public static function check(Request $request): bool
    {
        if (session()->has('user')) {
            return true;
        }

        if ($request->isJson() || config('app.env') === 'test') {
            return self::checkToken(self::getToken($request), $user);
        }

        return false;
    }

    public static function remember(): bool
    {
        return cookies()->has('user');
    }

    public static function user(Request $request): Model|false|null
    {
        if (session()->has('user')) {
            $user = session()->get('user');

            if (is_null($user)) {
                return null;
            }

            return User::find($user['id']);
        }

        if (! self::checkToken(self::getToken($request), $user)) {
            return null;
        }

        return $user;
    }

    public static function forget(): void
    {
        session()->forget(['user', 'history', 'csrf_token']);

        if (self::remember()) {
            cookies()->delete('user');
        }
    }
}
