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
	$secure = isset($_SERVER['HTTPS']);
	$expire = time() + (3600 * 24 * 30); //1 month

	return setcookie(
		$name, //name
		$value, //value
		$expire, //expire
		'/', //path
		WEB_DOMAIN, //domain
		$secure, //is secure?
		true //HTTP only
	);
}

/**
 * return cookie data
 *
 * @param  string $name cookie name
 * @return void
 */
function get_cookie(string $name)
{
	return cookie_has($name) ? $_COOKIE[$name] : '';
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
function delete_cookie(string $name)
{
	unset($_COOKIE[$name]);
}
