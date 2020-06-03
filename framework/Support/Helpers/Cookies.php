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

/**
 * create cookie and set data
 *
 * @param  string $name cookie name
 * @param  string $value cookie value content
 * @return bool returns true or false
 */
function create_cookie(string $name, string $value): bool
{
	return setcookie($name, $value, time() + (3600 * 24 * 30), '/');
}

/**
 * return cookie data
 *
 * @param  string $name cookie name
 * @return string
 */
function get_cookie(string $name): string
{
	return $_COOKIE[$name] ?? '';
}

/**
 * check if cookie exists
 *
 * @param  string $name cookie name
 * @return bool returns true or false
 */
function cookie_has(string $name): bool
{
	return isset($_COOKIE[$name]);
}

/**
 * delete cookie by name
 *
 * @param  string $name cookie name
 * @return void
 */
function delete_cookie(string $name): bool
{
	return setcookie($name, '', time() - 3600, '/');
}
