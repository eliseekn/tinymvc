<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
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
     * @param  string $name
     * @param  string $value
     * @param  int $expires
     * @param  string $domain
     * @param  bool $secure
     * @return bool
     */
    public static function create(
        string $name,
        string $value,
        int $expires = 3600,
        string $domain = '',
        bool $secure = false
    ): bool {
        return create_cookie($name, $value, $expires, $domain, $secure);
    }
    
    /**
     * get cookie data
     *
     * @param  string $name
     * @return string
     */
    public static function get(string $name): string
    {
        return get_cookie($name);
    }
    
    /**
     * check if cookie exists
     *
     * @param  string $name
     * @return bool
     */
    public static function has(string $name): bool
    {
        return cookie_has($name);
    }
    
    /**
     * delete cookie
     *
     * @param  string $name
     * @return bool
     */
    public static function delete(string $name): bool
    {
        return delete_cookie($name);
    }
}
