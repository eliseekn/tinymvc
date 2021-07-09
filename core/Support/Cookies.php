<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support;

/**
 * Manage cookies
 */
class Cookies
{    
    public static function create(string $name, string $value, int $expire = 3600, bool $secure = false, string $domain = '') 
    {
        $value = config('security.encryption.cookies') ? Encryption::encrypt($value) : $value;
		return setcookie(config('app.name') . '_' . $name, $value, time() + $expire, '/', $domain, $secure, true);
    }
    
    public static function get(string $name)
    {
        $value = $_COOKIE[config('app.name') . '_' . $name] ?? '';
		return config('security.encryption.cookies') ? Encryption::decrypt($value) : $value;
    }
    
    public static function has(string $name)
    {
        return isset($_COOKIE[config('app.name') . '_' . $name]);
    }
    
    public static function delete(string $name)
    {
        return setcookie(config('app.name') . '_' . $name, '', time() - 3600, '/');
    }
}
