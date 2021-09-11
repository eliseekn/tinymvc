<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support;

/**
 * Manage session
 */
class Session
{
    private static function start()
	{
		if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.gc_maxlifetime', config('security.session.lifetime'));
            session_set_cookie_params(config('security.session.lifetime'));
			session_start();
		}
	}

    public static function create(string $name, $data)
    {
        self::start();
		$_SESSION[strtolower(config('app.name')) . '_' . $name] = $data;
    }
    
    public static function get(string $name, $default = null)
    {
        self::start();
        return $_SESSION[strtolower(config('app.name')) . '_' . $name] ?? $default;
    }
    
    public static function has(string $name): bool
    {
        self::start();
		return isset($_SESSION[strtolower(config('app.name')) . '_' . $name]);
    }
    
    public static function forget(string ...$names)
    {
        self::start();
        
        foreach ($names as $name) {
            unset($_SESSION[strtolower(config('app.name')) . '_' . $name]);
        }
    }
    
    /**
     * Get session data and close it
     */
    public static function pull(string $name)
    {
        $data = self::get($name);
        self::forget($name);
        return $data;
    }
    
    /**
     * Add data to session or create if empty
     */
    public static function push(string $name, $data, $default = null)
    {
        $stored_data = self::get($name, $default);

        if (empty($stored_data)) {
            $stored_data = $data;
        } else {
            if (is_array($stored_data)) {
                $stored_data = array_merge($stored_data, $data);
            } elseif (is_string($stored_data)) {
                $stored_data .= $data;
            } elseif (is_numeric($stored_data)) {
                $stored_data += $data;
            } elseif (is_object($stored_data)) {
                $stored_data = (object) array_merge((array) $stored_data, (array) $data);
            }
        }

        $_SESSION[strtolower(config('app.name')) . '_' . $name] = $stored_data;
    }
}
