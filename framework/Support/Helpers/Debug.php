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
        if (!Storage::path(config('storage.logs'))->isDir()) {
            Storage::path(config('storage.logs'))->createDir();
        }

        error_log($message);
    }
}
