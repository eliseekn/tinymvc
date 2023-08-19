<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support;

/**
 * Manage cookies
 */
class Cookies
{    
    public function create(string $name, string $value, int $expire = 3600, bool $secure = false, string $domain = '') : bool
    {
        $value = config('security.encryption.cookies') ? Encryption::encrypt($value) : $value;
		return setcookie(config('app.name') . '_' . $name, $value, time() + $expire, '/', $domain, $secure, true);
    }
    
    public function get(string $name): mixed
    {
        $value = $_COOKIE[config('app.name') . '_' . $name] ?? '';
		return config('security.encryption.cookies') ? Encryption::decrypt($value) : $value;
    }
    
    public function has(string $name): bool
    {
        return isset($_COOKIE[config('app.name') . '_' . $name]);
    }
    
    public function delete(array|string $names): void
    {
        $names = parse_array($names);

        foreach ($names as $name) {
            setcookie(config('app.name') . '_' . $name, '', time() - 3600, '/');
        }
    }
}
