<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http\Auth;

use App\Database\Models\TokenModel;
use App\Enums\TokenDescription;
use Core\Database\Entity;
use Core\Enums\HttpAuthMethod;
use Core\Exceptions\CoreException;
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

    public static function attempt(Authenticable $auth, mixed &$user = null): bool
    {
        $identifier = $auth->getProviderIdentifier();

        session()->push('auth_attempts', 1, 0);
        $credentials = request()->inputs()->only([$identifier, 'password']);

        if (! self::checkCredentials($auth, (string) ($credentials[$identifier] ?? ''), (string) ($credentials['password'] ?? ''), $user)) {
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
        session()->create(self::sessionKey($auth), self::sessionData($user));

        if (request()->inputs()->has('remember')) {
            cookies()->create(self::sessionKey($auth), $credentials[$identifier], 3600 * 24 * 365);
        }

        return true;
    }

    public static function checkCredentials(Authenticable $auth, string $identifier, string $password, mixed &$user): bool
    {
        $user = $auth->getEntity($identifier);

        return $user && Encryption::check($password, $user->getPassword());
    }

    public static function hasProvider(string $name): bool
    {
        return $name !== 'default' && ! is_null(config('security.auth.providers.'.$name.'.entity'));
    }

    /**
     * Get the authenticable instance of a configured provider.
     */
    public static function provider(string $name = 'user'): Authenticable
    {
        $entity = config('security.auth.providers.'.$name.'.entity');

        if (is_null($entity)) {
            throw new CoreException("Unknown auth provider '$name'");
        }

        $auth = new $entity;

        if (! $auth instanceof Authenticable) {
            throw new CoreException("Invalid Authenticable instance '$entity'");
        }

        return $auth;
    }

    /**
     * Check api token. The provider defaults to the one the token was issued for.
     */
    public static function checkToken(string $token, ?Entity &$user, ?Authenticable $auth = null): bool
    {
        $user = null;
        $token = TokenModel::findByValue($token);

        if (! $token || $token->isExpired()) {
            return false;
        }

        $tokenProvider = $token->getAuthProvider() ?? 'user';

        if ($auth && $auth->getProviderName() !== $tokenProvider) {
            return false;
        }

        $auth ??= self::provider($tokenProvider);
        $user = $auth->getEntity($token->getIdentifier());

        return ! is_null($user);
    }

    public static function createToken(Authenticable $auth, ?string $identifier = null): string
    {
        $token = TokenModel::generate(
            $identifier ?? $auth->getIdentifierValue(),
            TokenDescription::AUTHENTICATION->value,
            generate_token(),
            $auth->getProviderName()
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

    public static function check(string $provider = 'user'): bool
    {
        return ! is_null(self::user(self::provider($provider)));
    }

    /**
     * Session and cookie key of a provider, so that providers never share a login.
     */
    public static function sessionKey(Authenticable|string $provider): string
    {
        return 'auth_'.($provider instanceof Authenticable ? $provider->getProviderName() : $provider);
    }

    /**
     * Only the identifier is stored, the entity is reloaded on each request.
     */
    public static function sessionData(Authenticable $auth): array
    {
        return [$auth->getProviderIdentifier() => $auth->getIdentifierValue()];
    }

    public static function remember(string $provider = 'user'): bool
    {
        return cookies()->has(self::sessionKey($provider));
    }

    public static function user(Authenticable $auth): ?Entity
    {
        $identifier = $auth->getProviderIdentifier();
        $provider = $auth->getProviderName();

        if (session()->has(self::sessionKey($provider))) {
            $data = session()->get(self::sessionKey($provider));

            if (! is_array($data) || ! isset($data[$identifier])) {
                return null;
            }

            return $auth->getEntity((string) $data[$identifier]);
        }

        if (empty(request()->getHttpAuth())) {
            return null;
        }

        $token = TokenModel::findByValue(self::getToken());

        if (! $token || $token->isExpired() || ($token->getAuthProvider() ?? 'user') !== $provider) {
            return null;
        }

        return $auth->getEntity($token->getIdentifier());
    }

    /**
     * Forget the login of a provider, or of every provider when none is given.
     */
    public static function forget(?string $provider = null): void
    {
        $providers = $provider
            ? [$provider]
            : array_diff(array_keys(config('security.auth.providers')), ['default']);

        foreach ($providers as $name) {
            session()->forget(self::sessionKey($name));

            if (self::remember($name)) {
                cookies()->delete(self::sessionKey($name));
            }
        }

        session()->forget(['history', 'csrf_token']);
    }

    public static function setSession(AuthEntity $user): void
    {
        session()->regenerate();
        session()->create(self::sessionKey($user), self::sessionData($user));
    }
}
