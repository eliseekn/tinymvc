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
 * Miscellaneous utils functions
 */

/**
 * slug generator
 *
 * @param  string $str
 * @param  string $separator
 * @return string
 */
function generate_slug(string $str, string $separator = '-'): string
{
	$slug = preg_replace('/[^a-zA-Z0-9]/', $separator, $str);
	$slug = strtolower(trim($slug, $separator));
	$slug = preg_replace('/\-{2,}/', $separator, $slug);
	return $slug;
}

/**
 * exerpt generator
 *
 * @param  string $text
 * @param  int $size
 * @param  string $end_string
 * @return string
 */
function generate_exerpt(string $text, int $size = 290, string $end_string = '[...]'): string
{
	return mb_strimwidth($text, 0, $size, $end_string);
}
