<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\System;

/**
 * Manage session
 */
class Session
{    
    /**
	 * start session
	 *
	 * @return void
     * @link  https://stackoverflow.com/questions/8311320/how-to-change-the-session-timeout-in-php/8311400
	 */
    private static function start(): void
	{
		if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.gc_maxlifetime', config('security.session.lifetime'));
            session_set_cookie_params(config('security.session.lifetime'));
			session_start();
		}
	}

    /**
     * create new session
     *
     * @param  string $name
     * @param  mixed $data
     * @return void
     */
    public static function create(string $name, $data): void
    {
        self::start();
		$_SESSION[strtolower(config('app.name')) . '_' . $name] = $data;
    }
    
    /**
     * get session data
     *
     * @param  string $name
     * @param  mixed $default
     * @return mixed
     */
    public static function get(string $name, $default = null)
    {
        self::start();
        return $_SESSION[strtolower(config('app.name')) . '_' . $name] ?? $default;
    }
    
    /**
     * check if session exists
     *
     * @param  string $name
     * @return bool
     */
    public static function has(string $name): bool
    {
        self::start();
		return isset($_SESSION[strtolower(config('app.name')) . '_' . $name]);
    }
    
    /**
     * flush session
     *
     * @param  string[] $names
     * @return void
     */
    public static function flush(string ...$names): void
    {
        self::start();
        
        foreach ($names as $name) {
            unset($_SESSION[strtolower(config('app.name')) . '_' . $name]);
        }
    }
    
    /**
     * get session data and close it
     *
     * @param  string $name
     * @return mixed
     */
    public static function pull(string $name)
    {
        $data = self::get($name);
        self::flush($name);
        return $data;
    }
    
    /**
     * add data to session or create if empty
     *
     * @param  string $name
     * @param  mixed $data
     * @param  mixed $default
     * @return void
     */
    public static function put(string $name, $data, $default = null): void
    {
        $stored_data = self::get($name, $default);

        if (empty($stored_data)) {
            $stored_data = $data;
        } else {
            if (is_array($stored_data)) {
                $stored_data = array_merge($stored_data, $data);
            } else if (is_string($stored_data)) {
                $stored_data .= $data;
            } else if (is_numeric($stored_data)) {
                $stored_data += $data;
            }
        }

        $_SESSION[strtolower(config('app.name')) . '_' . $name] = $stored_data;
    }
}
