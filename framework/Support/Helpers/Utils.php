<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Configula\ConfigFactory;

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

if (!function_exists('config')) {	
	/**
	 * read configuration
	 *
	 * @param  string $path
	 * @return mixed
	 */
	function config(string $path = '')
	{
		$config = ConfigFactory::loadPath(APP_ROOT . 'config' . DIRECTORY_SEPARATOR . 'app.php');
		return $config($path, '');
	}
}

if (!function_exists('get_file_extension')) {	
	/**
	 * get file extension
	 *
	 * @param  string $file
	 * @return string
	 */
	function get_file_extension(string $file): string
	{
		if (empty($file) || strpos($file, '.') === false) {
            return '';
		}
		
		$file_ext = explode('.', $file);
		return $file_ext === false ? '' : end($file_ext);
	}
}

if (!function_exists('get_file_name')) {	
	/**
	 * get file name
	 *
	 * @param  string $file
	 * @return string
	 */
	function get_file_name(string $file): string
	{
		if (empty($file) || strpos($file, '.') === false) {
            return '';
		}
		
		$filename = explode('.', $file);
		return $filename === false ? '' : $filename[0];
	}
}

if (!function_exists('time_elapsed')) {	
	/**
	 * get time elapsed
	 *
	 * @param  mixed $datetime
	 * @param  int $level
	 * @return string
	 * @link   https://stackoverflow.com/a/18602474
	 */
	function time_elapsed($datetime, int $level = 7): string
	{
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);
	
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;
	
		$string = array(
			'y' => __('year'),
			'm' => __('month'),
			'w' => __('week'),
			'd' => __('day'),
			'h' => __('hr'),
			'i' => __('min'),
			's' => __('sec'),
		);

		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v; // . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}
	
		$string = array_slice($string, 0, $level);
		return $string ? implode(', ', $string) . __('ago') : __('now');
	}
}

if (!function_exists('__')) {    
    /**
     * return translated word or expression
     *
     * @param  string $expr
     * @param  bool $app_config use application language configuration
     * @return string
     */
    function __(string $expr, bool $app_config = false): string
    {
        $lang = $app_config ? config('app.lang') : user_session()->lang;

        require 'resources/lang/' . $lang . '.php';
        return $$expr;
    }
}
