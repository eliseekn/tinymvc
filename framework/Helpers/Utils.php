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
 * Miscellaneous utils functions
 */

/**
 * slug generator
 *
 * @param  string $str original string
 * @param  string $separator words separator
 * @return string returns generated slug
 */
function generate_slug(string $str, string $separator = '-'): string
{
	$slug = strtolower(trim($str, $separator));
	$slug = preg_replace('/[^a-zA-Z0-9]/', $separator, $slug);
	$slug = preg_replace('/\-{2,}/', $separator, $slug);
	return $slug;
}

/**
 * truncate string
 *
 * @param  string $str original string
 * @param  int $size size of the truncated string
 * @param  string $end_string content of end string
 * @return string returns truncated string
 */
function truncate(string $str, int $size, string $end_string = '[...]'): string
{
	return mb_strimwidth($str, 0, $size, $end_string);
}

/**
 * random string generator
 *
 * @param  int $size random string size
 * @param  bool $alphanumeric use alphanumeric characters
 * @return string returns generated random string
 * @link   https://www.php.net/manual/en/function.str-shuffle.php
 */
function random_string(int $size = 10, bool $alphanumeric = false): string
{
	$chars = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$chars .= $alphanumeric ? '0123456789' : '';
	return substr(str_shuffle($chars), 0, $size);
}