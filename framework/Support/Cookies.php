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
     * @param  mixed $name
     * @param  mixed $data
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
     * @param  mixed $name
     * @return void
     */
    public static function get(string $name)
    {
        return get_cookie($name);
    }
    
    /**
     * check if cookie exists
     *
     * @param  mixed $name
     * @return bool
     */
    public static function has(string $name): bool
    {
        return cookie_has($name);
    }
    
    /**
     * delete cookie
     *
     * @param  mixed $name
     * @return bool
     */
    public static function delete(string $name): bool
    {
        return delete_cookie($name);
    }
    
    /**
     * set user cookie data
     *
     * @param  mixed $data
     * @return bool
     */
    public static function setUser($value): bool
    {
        return self::create(config('app.name') . '_user', $value, 3600 * 24 * 365);
    }
    
    /**
     * get user cookie data
     *
     * @return mixed
     */
    public static function getUser()
    {
        return self::get(config('app.name') . '_user');
    }
    
    /**
     * check user cookie data
     *
     * @return bool
     */
    public static function hasUser(): bool
    {
        return self::has(config('app.name') . '_user');
    }
    
    /**
     * delete user cookie data
     *
     * @return bool
     */
    public static function deleteUser(): bool
    {
        return self::delete(config('app.name') . '_user');
    }
}
