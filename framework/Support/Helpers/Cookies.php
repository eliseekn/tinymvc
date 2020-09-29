<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

/**
 * Cookies management functions
 */

if (!function_exists('create_cookie')) {
	/**
	 * create cookie and set value
	 *
	 * @param  string $name
	 * @param  string $value
	 * @param  int $expires in seconds (default 1 hour)
	 * @param  string $domain
	 * @param  bool $secure
	 * @return bool
	 */
	function create_cookie(
		string $name,
		string $value,
		int $expires = 3600,
		string $domain = '',
		bool $secure = false
	): bool {
		return setcookie($name, $value, time() + $expires, '/', $domain, $secure, true);
	}
}

if (!function_exists('get_cookie')) {
	/**
	 * return cookie value
	 *
	 * @param  string $name
	 * @return string
	 */
	function get_cookie(string $name): string
	{
		return $_COOKIE[$name] ?? '';
	}
}

if (!function_exists('cookie_has')) {
	/**
	 * check if cookie exists
	 *
	 * @param  string $name
	 * @return bool
	 */
	function cookie_has(string $name): bool
	{
		return isset($_COOKIE[$name]);
	}
}

if (!function_exists('delete_cookie')) {
	/**
	 * delete cookie by name
	 *
	 * @param  string $name
	 * @return bool
	 */
	function delete_cookie(string $name): bool
	{
		return setcookie($name, '', time() - 3600, '/');
	}
}

if (!function_exists('create_user_cookie')) {
	/**
	 * create user cookie
	 *
	 * @param  string $value
	 * @return bool
	 */
	function create_user_cookie(string $value): bool 
	{
		return create_cookie(config('app.name') . '_user', $value, 3600 * 24 * 365);
	}
}

if (!function_exists('get_user_cookie')) {
	/**
	 * return user cookie
	 *
	 * @return string
	 */
	function get_user_cookie(): string
	{
		return get_cookie(config('app.name') . '_user');
	}
}

if (!function_exists('cookie_has_user')) {
	/**
	 * check if user cookie exists
	 *
	 * @return bool
	 */
	function cookie_has_user(): bool
	{
		return cookie_has(config('app.name') . '_user');
	}
}

if (!function_exists('delete_user_cookie')) {
	/**
	 * delete user cookie
	 *
	 * @return void
	 */
	function delete_user_cookie(): void
	{
		delete_cookie(config('app.name') . '_user');
	}
}
