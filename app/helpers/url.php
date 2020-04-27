<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Miscellaneous URL utils functions
 */

/**
 * generate abosulte url path
 *
 * @param  string $url
 * @return string
 */
function base_url(string $url): string
{
	return WEB_DOMAIN . $url;
}

/**
 * redirect to location
 *
 * @param  mixed $location
 * @return void
 */
function redirect(string $location): void
{
	header('Location: ' . base_url($location));
	exit();
}
