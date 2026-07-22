<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http;

/**
 * Manage session.
 */
class Session
{
    public function __construct()
    {
        $this->start();
    }

    protected function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.gc_maxlifetime', config('security.session.lifetime'));
            session_set_cookie_params(config('security.session.lifetime'));
            session_start();
        }
    }

    public function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public function create(string $name, mixed $data): void
    {
        $_SESSION[strtolower(config('app.name')).'_'.$name] = $data;
    }

    public function get(string $name, $default = null): mixed
    {
        return $_SESSION[strtolower(config('app.name')).'_'.$name] ?? $default;
    }

    public function has(string $name): bool
    {
        return isset($_SESSION[strtolower(config('app.name')).'_'.$name]);
    }

    public function forget(array|string $names): void
    {
        $names = parse_array($names);

        foreach ($names as $name) {
            unset($_SESSION[strtolower(config('app.name')).'_'.$name]);
        }
    }

    /**
     * Get session data and close it.
     */
    public function pull(string $name): mixed
    {
        $data = $this->get($name);
        $this->forget($name);

        return $data;
    }

    /**
     * Add data to session or create if empty.
     */
    public function push(string $name, mixed $data, $default = null): void
    {
        $storedData = $this->get($name, $default);

        if (empty($storedData)) {
            $storedData = $data;
        } else {
            if (is_array($storedData)) {
                $storedData = array_merge($storedData, $data);
            } elseif (is_string($storedData)) {
                $storedData .= $data;
            } elseif (is_numeric($storedData)) {
                $storedData += $data;
            } elseif (is_object($storedData)) {
                $storedData = (object) array_merge((array) $storedData, (array) $data);
            }
        }

        $_SESSION[strtolower(config('app.name')).'_'.$name] = $storedData;
    }
}
