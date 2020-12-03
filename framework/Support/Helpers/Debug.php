<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Framework\Support\Storage;

/**
 * Debugging helpers function
 */

if (!function_exists('save_log')) {
    /**
	 * save log message to file
	 *
	 * @param  string $message 
	 * @return void
	 */
	function save_log(string $message): void
	{
        Storage::path(config('storage.logs'))->createDir();
        error_log($message);
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
