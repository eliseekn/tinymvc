<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Support;

/**
 * Manage cookie
 */
class Cookies
{    
    /**
     * create cookie
     *
     * @param  string $key
     * @param  string $value
     * @param  int $expires
     * @param  string $domain
     * @param  bool $secure
     * @return bool
     */
    public static function create(
        string $key,
        string $value,
        int $expires = 3600,
        string $domain = '',
        bool $secure = false
    ): bool {
        return create_cookie($key, $value, $expires, $domain, $secure);
    }
    
    /**
     * get cookie data
     *
     * @param  string $key
     * @return string
     */
    public static function get(string $key): string
    {
        return get_cookie(config('app.name') . '_' . $key);
    }
    
    /**
     * check if cookie exists
     *
     * @param  string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return cookie_has(config('app.name') . '_' . $key);
    }
    
    /**
     * delete cookie
     *
     * @param  string $key
     * @return bool
     */
    public static function delete(string $key): bool
    {
        return delete_cookie(config('app.name') . '_' . $key);
    }
}
