<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

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
		$_SESSION[$name] = $data;
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
		return $_SESSION[$name] ?? '';
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
		return isset($_SESSION[$name]);
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
		unset($_SESSION[$name]);
	}
}

if (!function_exists('flash_messages')) {
	/**
	 * check if flash messages exists
	 *
	 * @return bool
	 */
	function flash_messages(): bool
	{
		return session_has(config('app.name') . '_messages');
	}
}

if (!function_exists('get_flash_messages')) {
	/**
	 * get flash messages content
	 *
	 * @return mixed returns message content
	 */
	function get_flash_messages()
	{
		$flash_messages = get_session(config('app.name') . '_messages');
		close_session(config('app.name') . '_messages');
		return $flash_messages;
	}
}

if (!function_exists('get_user_session')) {
	/**
	 * get user session data
	 *
	 * @return mixed
	 */
	function get_user_session()
	{
		return get_session(config('app.name') . '_user');
	}
}
