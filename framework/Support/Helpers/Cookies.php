<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
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
	 * @param  string $name name of cookie
	 * @param  string $value cookie value content
	 * @param  int $expire expire time in seconds (default 1 hour)
	 * @param  string $domain
	 * @param  bool $secure secure cookie
	 * @return bool
	 */
	function create_cookie(
		string $name,
		string $value,
		int $expire = 3600,
		string $domain = '',
		bool $secure = false
	): bool {
		return setcookie($name, $value, time() + $expire, '/', $domain, $secure, true);
	}
}

if (!function_exists('get_cookie')) {
	/**
	 * return cookie value
	 *
	 * @param  string $name name of cookie
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
	 * @param  string $name name of cookie
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
	 * @param  string $name name of cookie
	 * @return void
	 */
	function delete_cookie(string $name): bool
	{
		return setcookie($name, '', time() - 3600, '/');
	}
}
