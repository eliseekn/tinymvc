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
 * generate slug from string with utf8 encoding
 *
 * @param  string $str original string
 * @param  string $separator words separator
 * @return string returns generated slug
 * @link   https://ourcodeworld.com/articles/read/253/creating-url-slugs-properly-in-php-including-transliteration-support-for-utf-8
 */
function slugify(string $str, string $separator = '-'): string
{
	return strtolower(
		trim(
			preg_replace(
				'~[^0-9a-z]+~i',
				$separator,
				html_entity_decode(
					preg_replace(
						'~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i',
						'$1',
						htmlentities($str, ENT_QUOTES, 'UTF-8')
					),
					ENT_QUOTES,
					'UTF-8'
				)
			),
			$separator
		)
	);
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

/**
 * random color generator
 *
 * @return string
 * @link   https://stackoverflow.com/questions/5614530/generating-a-random-hex-color-code-with-php
 */
function random_color(): string
{
	$chars = 'ABCDEF0123456789';
	$color = '#';
	
	for ( $i = 0; $i < 6; $i++ ) {
		$color .= $chars[rand(0, strlen($chars) - 1)];
	}

	return $color;
}