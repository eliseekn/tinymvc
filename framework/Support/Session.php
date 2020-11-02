<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Support;

use Framework\HTTP\Request;

/**
 * Manage session
 */
class Session
{    
    /**
     * create new session
     *
     * @param  string $name
     * @param  mixed $data
     * @return void
     */
    public static function create(string $name, $data): void
    {
        create_session($name, $data);
    }
    
    /**
     * get session data
     *
     * @param  string $name
     * @return mixed
     */
    public static function get(string $name)
    {
        return get_session($name);
    }
    
    /**
     * check if session exists
     *
     * @param  string $name
     * @return bool
     */
    public static function has(string $name): bool
    {
        return session_has($name);
    }
    
    /**
     * close session
     *
     * @param  string $name
     * @return void
     */
    public static function close(string $name): void
    {
        close_session($name);
    }
    
    /**
     * set user session
     *
     * @param  mixed $data
     * @return void
     */
    public static function setUser($data): void
    {
        self::create(config('app.name') . '_user', $data);
    }
    
    /**
     * get user session data
     *
     * @return mixed
     */
    public static function getUser()
    {
        return self::get(config('app.name') . '_user');
    }
    
    /**
     * check user sessions
     *
     * @return bool
     */
    public static function hasUser(): bool
    {
        return self::has(config('app.name') . '_user');
    }
    
    /**
     * colse user session
     *
     * @return void
     */
    public static function deleteUser(): void
    {
        self::close(config('app.name') . '_user');
    }

    /**
     * set history sesssion
     *
     * @return void
     */
    public static function setHistory(): void
    {
        $url = self::getHistory();

        foreach (config('session.history.excludes') as $exclude) {
            if (!url_exists($exclude)) {
                if (empty($url)) {
                    $url = [Request::getFullUri()];
                } else {
                    $url[] = Request::getFullUri();
                }
            }
        }

        self::create(config('app.name') . '_history', $url);
    }
    
    /**
     * get history session
     *
     * @return mixed
     */
    public static function getHistory()
    {
        return self::get(config('app.name') . '_history');
    }
    
    /**
     * close history session
     *
     * @return void
     */
    public static function clearHistory(): void
    {
        self::close(config('app.name') . '_history');
    }
}