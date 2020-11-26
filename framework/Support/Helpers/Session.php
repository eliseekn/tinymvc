<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

use Carbon\Carbon;

/**
 * Sessions management functions
 */

if (!function_exists('start_session')) {
	/**
	 * start session
	 *
	 * @return void
	 */
	function start_session(): void
	{
		if (session_status() === PHP_SESSION_NONE) {
            //https://stackoverflow.com/questions/8311320/how-to-change-the-session-timeout-in-php/8311400
            ini_set('session.gc_maxlifetime', config('session.lifetime'));
            session_set_cookie_params(config('session.lifetime'));
            
			session_start();
		}
	}
}

if (!function_exists('create_session')) {
	/**
	 * create session data
	 *
	 * @param  string $name
	 * @param  mixed $data
	 * @return void
	 */
	function create_session(string $name, $data): void
	{
		start_session();
		$_SESSION[config('app.name') . '_' . $name] = $data;
	}
}

if (!function_exists('get_session')) {
	/**
	 * get session data
	 *
	 * @param  string $name
	 * @return mixed returns session stored data
	 */
	function get_session(string $name)
	{
		start_session();
		return $_SESSION[config('app.name') . '_' . $name] ?? '';
	}
}

if (!function_exists('session_has')) {
	/**
	 * check if session exists
	 *
	 * @param  string $name
	 * @return bool returns true or false
	 */
	function session_has(string $name): bool
	{
		start_session();
		return isset($_SESSION[config('app.name') . '_' . $name]);
	}
}

if (!function_exists('close_session')) {
	/**
	 * delete session
	 *
	 * @param  string $name
	 * @return void
	 */
	function close_session(string $name): void
	{
		start_session();
		unset($_SESSION[config('app.name') . '_' . $name]);
	}
}

if (!function_exists('get_alerts')) {
	/**
	 * get session alerts data
	 *
	 * @return mixed
	 */
	function get_alerts()
	{
		$alerts = get_session('alerts');
		close_session('alerts');
		return $alerts;
	}
}

if (!function_exists('get_inputs')) {
	/**
	 * get session inputs data
	 *
	 * @return mixed
	 */
	function get_inputs()
	{
		$inputs = get_session('inputs');
		close_session('inputs');
		return (object) $inputs;
	}
}

if (!function_exists('get_errors')) {
	/**
	 * get session inputs data
	 *
	 * @return mixed
	 */
	function get_errors()
	{
		$errors = get_session('errors');
		close_session('errors');
		return (object) $errors;
	}
}

if (!function_exists('user_session')) {
	/**
	 * get user session data
	 *
	 * @return mixed
	 */
	function user_session()
	{
		return get_session('user');
	}
}

if (!function_exists('auth_attempts_exceeded')) {    
    /**
     * check auth attempts exceeded
     *
     * @return bool
     */
    function auth_attempts_exceeded(): bool
    {
        $unlock_timeout = Carbon::parse(get_session('auth_attempts_timeout'))->addMinutes(config('security.auth.unlock_timeout')) > Carbon::now();
        return session_has('auth_attempts') && get_session('auth_attempts') > config('security.auth.max_attempts') && $unlock_timeout;
    }
}