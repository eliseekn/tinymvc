<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\System;

/**
 * Manage session
 */
class Session
{    
    /**
	 * start session
	 *
	 * @return void
	 */
    private static function start(): void
	{
		if (session_status() === PHP_SESSION_NONE) {
            //https://stackoverflow.com/questions/8311320/how-to-change-the-session-timeout-in-php/8311400
            ini_set('session.gc_maxlifetime', config('session.lifetime'));
            session_set_cookie_params(config('session.lifetime'));
            
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
		$_SESSION[config('app.name') . '_' . $name] = $data;
    }
    
    /**
     * get session data
     *
     * @param  string $name
     * @return mixed
     */
    public static function get(string $name)
    {
        self::start();
		return $_SESSION[config('app.name') . '_' . $name] ?? '';
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
		return isset($_SESSION[config('app.name') . '_' . $name]);
    }
    
    /**
     * close session
     *
     * @param  string[] $names
     * @return void
     */
    public static function close(string ...$names): void
    {
        self::start();
        
        foreach ($names as $name) {
            unset($_SESSION[config('app.name') . '_' . $name]);
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
        self::close($name);
        return $data;
    }
}
