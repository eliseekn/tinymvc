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
 * Miscellaneous URL utils functions
 */

if (!function_exists('absolute_url')) {
	/**
	 * generate abosulte url
	 *
	 * @param  string $url
	 * @return string
	 */
	function absolute_url(string $url): string
	{
		return WEB_DOMAIN . $url;
	}
}

if (!function_exists('redirect_to')) {
	/**
	 * redirect to another location
	 *
	 * @param  string $location
	 * @return void
	 */
	function redirect_to(string $location): void
	{
		header('Location: ' . absolute_url($location));
		exit();
	}
}