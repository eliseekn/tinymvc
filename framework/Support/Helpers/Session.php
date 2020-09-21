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
			setcookie(session_name(), session_id(), time() + config('session.lifetime'));
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

if (!function_exists('create_browsing_history')) {
	/**
	 * create browsing history session
	 *
	 * @param  mixed $content
	 * @return void
	 */
	function create_browsing_history($content): void
	{
		create_session('browsing_history', $content);
	}
}

if (!function_exists('get_browsing_history')) {
	/**
	 * get browsing history content
	 *
	 * @return mixed
	 */
	function get_browsing_history()
	{
		return get_session('browsing_history');
	}
}

if (!function_exists('create_flash_messages')) {
	/**
	 * create flash messages session
	 *
	 * @param  string $title
	 * @param  mixed $content
	 * @return void
	 */
	function create_flash_messages(string $title, $content): void
	{
		create_session('flash_messages', [
			$title => $content
		]);
	}
}

if (!function_exists('session_has_flash_messages')) {
	/**
	 * check if flash messages exists
	 *
	 * @return bool
	 */
	function session_has_flash_messages(): bool
	{
		return session_has('flash_messages');
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
		$flash_messages = get_session('flash_messages');
		close_session('flash_messages');
		return $flash_messages;
	}
}

if (!function_exists('create_user_session')) {
	/**
	 * create user session data
	 *
	 * @param  mixed $data
	 * @return void
	 */
	function create_user_session($data): void
	{
		create_session(config('app.name') . '_user', $data);
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

if (!function_exists('session_has_user')) {
	/**
	 * check if user session exists
	 *
	 * @return mixed
	 */
	function session_has_user()
	{
		return session_has(config('app.name') . '_user');
	}
}

if (!function_exists('close_user_session')) {
	/**
	 * check if user session exists
	 *
	 * @return mixed
	 */
	function close_user_session()
	{
		return close_session(config('app.name') . '_user');
	}
}
