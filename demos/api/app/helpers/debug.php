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
 * Miscellaneous debugging utils functions
 */

/**
 * save log message to file
 *
 * @param  string $destination absolute path logs destination directory without trailing slash
 * @param  int $type log message type
 * @param  string $message message content
 * @return void
 */
function save_log(string $destination, string $type, string $message): void
{
	$log = '[' . date('H:i:s', time()) . '] [' . $type . '] ' . $message . PHP_EOL;
	$log_file = $destination . '/logs_' . date('m_d_y', time()) . '.txt';
	file_put_contents($log_file, $log, FILE_APPEND | LOCK_EX);
}

/**
 * print informations about variables and exit
 *
 * @param  mixed $data variables
 * @return void
 */
function dump_exit(...$data): void
{
	foreach ($data as $d) {
		echo '<pre>';
		print_r($d);
		echo '</pre>';
	}

	exit();
}
