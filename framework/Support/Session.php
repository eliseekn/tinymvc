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
     * @param  string $key
     * @param  mixed $data
     * @return void
     */
    public static function create(string $key, $data): void
    {
        create_session($key, $data);
    }
    
    /**
     * get session data
     *
     * @param  string $key
     * @return mixed
     */
    public static function get(string $key)
    {
        return get_session($key);
    }
    
    /**
     * check if session exists
     *
     * @param  string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return session_has($key);
    }
    
    /**
     * close session
     *
     * @param  string[] $key
     * @return void
     */
    public static function close(string ...$keys): void
    {
        foreach ($keys as $key) {
            close_session($key);
        }
    }
    
    /**
     * set history sesssion
     *
     * @return void
     */
    public static function history(): void
    {
        $url = self::get('history');

        foreach (config('session.history.excludes') as $exclude) {
            if (!url_exists($exclude)) {
                if (empty($url)) {
                    $url = [Request::getFullUri()];
                } else {
                    $url[] = Request::getFullUri();
                }
            }
        }

        self::create('history', $url);
    }
}