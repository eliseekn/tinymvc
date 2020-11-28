<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Debug
 * 
 * Debugging class
 */

if (!function_exists('write_log')) {
    /**
	 * save log message to file
	 *
	 * @param  string $folder
	 * @param  string $type (INFOS, ERROR, DEBUG...)
	 * @param  string $message 
	 * @return void
	 */
	function write_log(string $folder, string $type, string $message): void
	{
		$log = '[' . date('H:i:s') . '] [' . $type . '] ' . $message . PHP_EOL;
		$log_file = APP_ROOT . $folder . DIRECTORY_SEPARATOR . 'logs_' . date('m_d_y') . '.txt';
		file_put_contents($log_file, $log, FILE_APPEND | LOCK_EX);
	}
}

if (!function_exists('dump_vars')) {
    /**
	 * print informations about variables and exit
	 *
	 * @param  mixed $data
	 * @return void
	 */
	function dump_vars(...$data): void
	{
		foreach ($data as $d) {
			krumo($d);
		}

		exit();
	}
}
