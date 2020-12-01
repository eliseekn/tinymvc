<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
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
     * @param  string[] $name
     * @return void
     */
    public static function close(string ...$names): void
    {
        foreach ($names as $name) {
            close_session($name);
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

        //excludes api uri from browser history
        if (!url_exists('api')) {
            if (empty($url)) {
                $url = [Request::getFullUri()];
            } else {
                $url[] = Request::getFullUri();
            }
        }

        self::create('history', $url);
    }
}