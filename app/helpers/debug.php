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
 * Miscellaneous debugging utils functions
 */

/**
 * save log message to file
 *
 * @param  string $type
 * @param  string $message
 * @return void
 */
function save_log(string $type, string $message): void
{
	switch ($type) {
		case -1:
			$log = 'ERROR: ' . $message . PHP_EOL;
			break;
		case 0:
			$log = 'WARNING: ' . $message . PHP_EOL;
			break;
		case 1:
			$log = 'DEBUG: ' . $message . PHP_EOL;
			break;
	}

	$current_date = date('m_d_Y', time());
	$log_file = LOGS_DIR . 'logs_' . $current_date . '.txt';
	file_put_contents($log_file, $log, FILE_APPEND | LOCK_EX);
}

/**
 * print informations about variables and exit
 *
 * @param  mixed $data
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
