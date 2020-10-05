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
     * create
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
     * get
     *
     * @param  mixed $name
     * @return void
     */
    public static function get(string $name)
    {
        return get_cookie($name);
    }
    
    /**
     * has
     *
     * @param  mixed $name
     * @return bool
     */
    public static function has(string $name): bool
    {
        return cookie_has($name);
    }
    
    /**
     * close
     *
     * @param  mixed $name
     * @return bool
     */
    public static function delete(string $name): bool
    {
        return delete_cookie($name);
    }
    
    /**
     * setUser
     *
     * @param  mixed $data
     * @return bool
     */
    public static function setUser($value): bool
    {
        return self::create(config('app.name') . '_user', $value, 3600 * 24 * 365);
    }
    
    /**
     * getUser
     *
     * @return mixed
     */
    public static function getUser()
    {
        return self::get(config('app.name') . '_user');
    }
    
    /**
     * hasUser
     *
     * @return bool
     */
    public static function hasUser(): bool
    {
        return self::has(config('app.name') . '_user');
    }
    
    /**
     * deleteUser
     *
     * @return bool
     */
    public static function deleteUser(): bool
    {
        return self::delete(config('app.name') . '_user');
    }
}
