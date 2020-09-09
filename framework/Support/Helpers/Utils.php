<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

/**
 * Miscellaneous utils functions
 */

if (!function_exists('slugify')) {
	/**
	 * generate slug from string with utf8 encoding
	 *
	 * @param  string $str original string
	 * @param  string $separator
	 * @return string
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
}

if (!function_exists('truncate')) {
	/**
	 * truncate string
	 *
	 * @param  string $str original string
	 * @param  int $length length of truncated string
	 * @param  string $end end of truncated string
	 * @return string
	 */
	function truncate(string $str, int $length, string $end = '[...]'): string
	{
		return mb_strimwidth($str, 0, $length, $end);
	}
}

if (!function_exists('random_string')) {
	/**
	 * random string generator
	 *
	 * @param  int $length
	 * @param  bool $alphanumeric use alphanumeric
	 * @return string
	 * @link   https://www.php.net/manual/en/function.str-shuffle.php
	 */
	function random_string(int $length = 10, bool $alphanumeric = false): string
	{
		$chars = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$chars .= $alphanumeric ? '0123456789' : '';
		return substr(str_shuffle($chars), 0, $length);
	}
}

if (!function_exists('generate_csv')) {	
	/**
	 * generate csv file from data
	 *
	 * @param  string $filename
	 * @param  array $data
	 * @param  array|null $headers
	 * @param  string|null $output
	 * @return void
	 */
	function generate_csv(string $filename, array $data, ?array $headers = null, ?string $output = null): void
	{
		if (is_null($output)) {
    		header("Content-Description: File Transfer");
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="' . $filename . '"');
			header("Cache-Control: no-cache");
			header("Pragma: no-cache");
			header("Expires: 0");
			$handle = fopen('php://output', 'w');
		} else {
			$handle = fopen($output, 'w');
		}

		//insert headers
		if (!is_null($headers)) {
			fputcsv($handle, $headers);
		}

		foreach ($data as $row) {
			fputcsv($handle, $row);
		}

		fclose($handle);

		exit();
	}
}
