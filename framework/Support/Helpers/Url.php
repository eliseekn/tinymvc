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

/**
 * generate abosulte url
 *
 * @param  string $url controller and actions names
 * @return string returns absolute url
 */
function absolute_url(string $url): string
{
	return WEB_DOMAIN . $url;
}

/**
 * redirect to another location
 *
 * @param  mixed $location controller and actions names
 * @return void
 */
function redirect_to(string $location): void
{
	header('Location: ' . absolute_url($location));
	exit();
}
