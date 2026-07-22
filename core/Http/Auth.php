<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http;

use App\Database\Entities\User;
use App\Database\Models\TokenModel;
use App\Database\Models\UserModel;
use App\Enums\TokenDescription;
use Core\Database\Entity;
use Core\Enums\AppEnv;
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

    public static function attempt(?User &$user): bool
    {
        session()->push('auth_attempts', 1, 0);
        $credentials = request()->inputs()->only([config('security.auth.identifier'), 'password']);

        if (! self::checkCredentials($credentials[config('security.auth.identifier')], $credentials['password'], $user)) {
            if (config('security.auth.max_attempts') > 0 && self::getAttempts() >= config('security.auth.max_attempts')) {
                response()
                    ->back()
                    ->with('auth_attempts_timeout', carbon()->addMinutes(config('security.auth.unlock_timeout'))->toDateTimeString())
                    ->send();
            }

            return false;
        }

        session()->forget(['auth_attempts', 'auth_attempts_timeout']);
        session()->regenerate();
        session()->create('user', $user->toArray());

        if (request()->inputs()->has('remember')) {
            cookies()->create('user', $user->getEmail(), 3600 * 24 * 365);
        }

        return true;
    }

    public static function checkCredentials(string $identifier, string $password, ?Entity &$user): bool
    {
        $user = UserModel::findByIdentifier($identifier);

        return $user && Encryption::check($password, $user->getPassword());
    }

    public static function checkToken(string $token, ?Entity &$user): bool
    {
        $token = TokenModel::findByValue($token);

        if (! $token) {
            $user = null;

            return false;
        }

        $user = UserModel::findByIdentifier($token->getIdentifier());

        return ! is_null($user);
    }

    public static function createToken(User $user): string
    {
        $identifier = $user->toModel()->getAttributes(config('security.auth.identifier'));

        $token = TokenModel::generate(
            $identifier,
            TokenDescription::AUTHENTICATION->value,
            generate_token()
        );

        return is_null($token) ? '' : encrypt($token->getValue());
    }

    public static function deleteToken(): bool
    {
        $token = TokenModel::findByValue(self::getToken());

        return $token && $token->toModel()->delete();
    }

    public static function getToken(): string
    {
        if (empty(request()->getHttpAuth())) {
            return '';
        }

        [$method, $token] = request()->getHttpAuth();

        return trim($method) !== HttpAuthMethod::BEARER ? '' : decrypt($token);
    }

    public static function check(): bool
    {
        if (session()->has('user')) {
            return true;
        }

        if (request()->isJson() || config('app.env') === AppEnv::TEST) {
            return self::checkToken(self::getToken(), $user);
        }

        return false;
    }

    public static function remember(): bool
    {
        return cookies()->has('user');
    }

    public static function user(): ?User
    {
        if (session()->has('user')) {
            $user = session()->get('user');

            if (is_null($user)) {
                return null;
            }

            return UserModel::findByIdentifier($user[config('security.auth.identifier')]);
        }

        if (empty(request()->getHttpAuth())) {
            return null;
        }

        $token = TokenModel::findByValue(self::getToken());

        if (! $token) {
            return null;
        }

        return UserModel::findByIdentifier($token->getIdentifier());
    }

    public static function forget(): void
    {
        session()->forget(['user', 'history', 'csrf_token']);

        if (self::remember()) {
            cookies()->delete('user');
        }
    }
}
